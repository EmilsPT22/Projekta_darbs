<?php

namespace App\Http\Controllers;

use App\Models\Internship;
use App\Models\Theme;
use Illuminate\Http\Request;
use App\Models\User;

class InternshipController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
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

        if ($user->role === 'student' && !$internship->students->contains($user->id)) {
            abort(403);
        }

        $addedStudents = $internship->students;
        $users = collect();

        if ($user->role === 'admin') {
            $users = User::where('role', 'student')
                ->whereNotIn('id', $addedStudents->pluck('id'))
                ->get();
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

        $internship->students()->detach($id);

        return back()->with('success', 'Student removed successfully.');
    }

    private function authorizeAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
    }
}
