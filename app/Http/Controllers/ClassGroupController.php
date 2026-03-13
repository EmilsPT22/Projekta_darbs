<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\User;
use Illuminate\Http\Request;

class ClassGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of class groups.
     */
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher'])) {
            abort(403);
        }

        $classGroups = ClassGroup::withCount('students')->get();

        return view('classgroups.index', compact('classGroups'));
    }

    /**
     * Show the form for creating a new class group.
     */
    public function create()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        return view('classgroups.create');
    }

    /**
     * Store a newly created class group.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        ClassGroup::create($request->all());

        return redirect()->route('classgroups.index')->with('success', 'Class group created successfully');
    }

    /**
     * Display the specified class group.
     */
    public function show(ClassGroup $classgroup)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher'])) {
            abort(403);
        }

        $students = $classgroup->students()->with('roles')->get();

        return view('classgroups.show', compact('classgroup', 'students'));
    }

    /**
     * Show the form for editing the specified class group.
     */
    public function edit(ClassGroup $classgroup)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        return view('classgroups.edit', compact('classgroup'));
    }

    /**
     * Update the specified class group.
     */
    public function update(Request $request, ClassGroup $classgroup)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $classgroup->update($request->all());

        return redirect()->route('classgroups.index')->with('success', 'Class group updated successfully');
    }

    /**
     * Remove the specified class group.
     */
    public function destroy(ClassGroup $classgroup)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $classgroup->delete();

        return redirect()->route('classgroups.index')->with('success', 'Class group deleted successfully');
    }

    /**
     * Assign students to a class group.
     */
    public function assignStudents(Request $request, ClassGroup $classgroup)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'internship_manager'])) {
            abort(403);
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->student_ids)->update([
            'class_group_id' => $classgroup->id,
        ]);

        return back()->with('success', 'Students assigned to class successfully');
    }
}
