<?php

namespace App\Http\Controllers;

use App\Models\DailyEntry;
use App\Models\Internship;
use App\Models\Theme;
use App\Models\User;
use Illuminate\Http\Request;

class DailyEntryController extends Controller
{
    public function index(Internship $internship)
    {
        $user = auth()->user();

        if ($user->hasRole('student')) {
            if (!$internship->students->contains($user->id)) {
                abort(403);
            }

            $entries = DailyEntry::where('internship_id', $internship->id)
                ->where('user_id', $user->id)
                ->get();
        } else {
            // Admin, internship manager, and teacher can view all entries
            $entries = DailyEntry::where('internship_id', $internship->id)->get();
        }

        return view('entries.index', compact('entries', 'internship'));
    }

    public function calendar(Internship $internship, User $student)
    {
        $user = auth()->user();

        // Students can only see their own calendar
        if ($user->hasRole('student')) {
            if (!$internship->students->contains($user->id)) {
                abort(403);
            }
            $entries = DailyEntry::where('internship_id', $internship->id)
                ->where('user_id', $user->id)
                ->get();
        } else {
            // Admin, internship manager, and teacher must specify which student's calendar to view
            if (!$internship->students->contains($student->id)) {
                abort(403);
            }
            $entries = DailyEntry::where('internship_id', $internship->id)
                ->where('user_id', $student->id)
                ->get();
        }

        return view('entries.calendar', compact('entries', 'internship', 'student'));
    }

public function create(Internship $internship)
{
    $user = auth()->user();

    if ($user->hasRole('student') && !$internship->students->contains($user->id)) {
        abort(403);
    }

    // Ensure student has themes assigned (in case themes were added after student was assigned)
    $this->assignThemesToStudent($internship, $user);

    $themes = Theme::where('internship_id', $internship->id)
        ->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereColumn('assigned_hours', '>', 'used_hours');
        })
        ->get();

    return view('entries.create', compact('internship', 'themes'));
}

    /**
     * Assign any missing themes to a student.
     */
    private function assignThemesToStudent(Internship $internship, $student)
    {
        $themes = Theme::where('internship_id', $internship->id)->get();

        foreach ($themes as $theme) {
            // Check if student already has this theme assigned
            $exists = $theme->users()
                ->where('user_id', $student->id)
                ->exists();

            if (!$exists) {
                $theme->users()->attach($student->id, [
                    'assigned_hours' => $theme->max_hours,
                    'used_hours' => 0,
                ]);
            }
        }
    }
    
    public function store(Request $request, Internship $internship)
    {
        $user = auth()->user();

        if ($user->hasRole('student') && !$internship->students->contains($user->id)) {
            abort(403);
        }

        $request->validate([
            'theme_id'     => 'required|exists:themes,id',
            'date'         => 'required|date|after_or_equal:' . $internship->start_date . '|before_or_equal:' . $internship->end_date,
            'location'     => 'required|in:remote,on-site,mixed',
            'time_from'    => 'required|date_format:H:i',
            'time_to'      => 'required|date_format:H:i|after:time_from',
            'credit_hours' => 'required|integer|min:1|max:12',
            'intern_comment' => 'nullable|string',
        ]);

        $theme = Theme::where('id', $request->theme_id)
            ->where('internship_id', $internship->id)
            ->firstOrFail();

        $pivot = $theme->users()
            ->where('user_id', $user->id)
            ->firstOrFail()
            ->pivot;

        $remaining = $pivot->assigned_hours - $pivot->used_hours;

        if ($request->credit_hours > $remaining) {
            return back()->withErrors([
                'credit_hours' => 'Only '.$remaining.' hours remaining for this plan.',
            ])->withInput();
        }

        $seconds = strtotime($request->time_to) - strtotime($request->time_from);
        $durationHours = $seconds / 3600;

        $totalUserHours = DailyEntry::where('internship_id', $internship->id)
            ->where('user_id', $user->id)
            ->sum('credit_hours');

        if ($totalUserHours + $request->credit_hours > 160) {
            return back()->withErrors([
                'credit_hours' => 'You cannot exceed the 160-hour limit for this internship.',
            ])->withInput();
        }

        DailyEntry::create([
            'internship_id' => $internship->id,
            'user_id' => $user->id,
            'theme_id' => $theme->id,
            'date' => $request->date,
            'location' => $request->location,
            'time_from' => $request->time_from,
            'time_to' => $request->time_to,
            'duration' => $durationHours . ' h',
            'credit_hours' => $request->credit_hours,
            'intern_comment' => $request->intern_comment,
        ]);

        $theme->users()->updateExistingPivot($user->id, [
            'used_hours' => $pivot->used_hours + $request->credit_hours,
        ]);

        return redirect()->route('entries.index', $internship->id)
            ->with('success', 'Entry added successfully');
    }

    public function studentEntries(Internship $internship, User $student)
    {
        // Admin, internship manager, and teacher can view student entries
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher'])) {
            abort(403);
        }

        $entries = DailyEntry::where('internship_id', $internship->id)
            ->where('user_id', $student->id)
            ->get();

        return view('entries.student', compact('entries', 'student', 'internship'));
    }

    public function edit(Internship $internship, DailyEntry $entry)
    {
        $user = auth()->user();
        
        // Admin and internship manager can edit/grade entries
        if (!$user->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        return view('entries.edit', compact('internship', 'entry'));
    }

    public function update(Request $request, Internship $internship, DailyEntry $entry)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $request->validate([
                'admin_comment' => 'nullable|string|max:1000',
                'grade' => 'nullable|integer|min:1|max:10',
            ]);

            $entry->update([
                'admin_comment' => $request->admin_comment,
                'grade' => $request->grade,
            ]);
        } elseif ($user->hasRole('internship_manager')) {
            // Internship manager can only change the grade
            $request->validate([
                'grade' => 'required|integer|min:1|max:10',
            ]);

            $entry->update([
                'grade' => $request->grade,
            ]);
        } else {
            abort(403);
        }

        return redirect()->route('entries.index', $internship->id)
            ->with('success', 'Entry feedback updated successfully');
    }
}
