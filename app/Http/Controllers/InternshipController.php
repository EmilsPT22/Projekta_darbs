<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Theme;
use App\Models\InternshipApplication;
use Illuminate\Http\Request;
use App\Models\User;

class InternshipController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            $internships = Internship::all();
        } elseif (auth()->user()->hasRole('teacher')) {
            // Teachers can view all internships
            $internships = Internship::all();
        } elseif (auth()->user()->hasRole('student')) {
            // Students see all internships (not just assigned ones)
            $internships = Internship::all();
        } else {
            $internships = auth()->user()->internships;
        }

        return view('internships.index', compact('internships'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('internships.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'length' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Internship::create($request->all());

        return redirect()->route('internships.index')->with('success', 'Internship created successfully');
    }

    public function show(Internship $internship)
    {
        $user = auth()->user();

        // Students can view any internship details (not just enrolled ones)
        // Only block if student tries to access and isn't enrolled AND isn't viewing details
        // Actually, let students view all internship details

        $addedStudents = $internship->students;
        $users = collect();

        if ($user->hasAnyRole(['admin', 'internship_manager'])) {
            // Get users who are students only (not teachers, admins, or managers)
            $users = User::role('student')
                ->whereNotIn('id', $addedStudents->pluck('id'))
                ->get();
        } elseif ($user->hasRole('teacher')) {
            // Teachers can view students but not manage them
            $users = $addedStudents;
        }

        return view('internships.show', compact('internship', 'users', 'addedStudents'));
    }

    public function edit(Internship $internship)
    {
        $this->authorizeAdmin();
        return view('internships.edit', compact('internship'));
    }

    public function update(Request $request, Internship $internship)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'length' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $internship->update($request->all());

        return redirect()->route('internships.index')->with('success', 'Internship updated successfully');
    }

    public function destroy(Internship $internship)
    {
        $this->authorizeAdmin();
        $internship->delete();

        return redirect()->route('internships.index')->with('success', 'Internship deleted successfully');
    }

    public function addStudent(Internship $internship, $id)
    {
        $this->authorizeAdmin();

        $student = User::findOrFail($id);

        if ($internship->students()->where('user_id', $id)->exists()) {
            return back()->with('error', 'This student is already added.');
        }

        // Check if student is already in another internship
        $existingInternship = $student->internships()->first();
        if ($existingInternship && $existingInternship->id !== $internship->id) {
            return back()->with('error', 'This student is already enrolled in another internship: ' . $existingInternship->name);
        }

        $internship->students()->attach($id);

        $themes = Theme::where('internship_id', $internship->id)->get();

        foreach ($themes as $theme) {
            $theme->users()->attach($student->id, [
                'assigned_hours' => $theme->max_hours,
                'used_hours' => 0,
            ]);
        }

        return back()->with('success', 'Student added successfully.');
    }

    public function removeStudent(Internship $internship, $id)
    {
        $this->authorizeAdmin();

        // Remove student from internship
        $internship->students()->detach($id);

        // Remove related themes
        $themes = Theme::where('internship_id', $internship->id)->get();
        foreach ($themes as $theme) {
            $theme->users()->detach($id);
        }

        // Delete the application so student can reapply
        InternshipApplication::where('internship_id', $internship->id)
            ->where('user_id', $id)
            ->delete();

        return back()->with('success', 'Student removed successfully.');
    }

    private function authorizeAdmin()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }
    }
}
