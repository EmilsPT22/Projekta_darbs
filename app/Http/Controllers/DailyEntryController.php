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

        if ($user->role === 'student') {
            if (!$internship->students->contains($user->id)) {
                abort(403);
            }

            $entries = DailyEntry::where('internship_id', $internship->id)
                ->where('user_id', $user->id)
                ->get();
        } else {
            $entries = DailyEntry::where('internship_id', $internship->id)->get();
        }

        return view('entries.index', compact('entries', 'internship'));
    }

public function create(Internship $internship)
{
    $user = auth()->user();

    if ($user->role === 'student' && !$internship->students->contains($user->id)) {
        abort(403);
    }

    $themes = Theme::where('internship_id', $internship->id)
        ->whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->whereColumn('assigned_hours', '>', 'used_hours');
        })
        ->get();

    return view('entries.create', compact('internship', 'themes'));
}
    
    public function store(Request $request, Internship $internship)
    {
        $user = auth()->user();

        if ($user->role !== 'student' || !$internship->students->contains($user->id)) {
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
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $entries = DailyEntry::where('internship_id', $internship->id)
            ->where('user_id', $student->id)
            ->get();

        return view('entries.student', compact('entries', 'student', 'internship'));
    }
}
