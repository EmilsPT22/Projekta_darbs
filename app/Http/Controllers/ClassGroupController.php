<?php

namespace App\Http\Controllers;

use App\Models\ClassGroup;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class ClassGroupController extends Controller
{
    use LogsActivity;
    /**
     * Display a listing of class groups.
     */
    public function index()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'teacher'])) {
            abort(403);
        }

        $classGroups = ClassGroup::with(['teacher'])->withCount('students')->get();

        return view('classgroups.index', compact('classGroups'));
    }

    /**
     * Show teachers and their assigned classes.
     */
    public function teachers()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $teachers = User::whereHas('roles', function($q) {
            $q->where('name', 'teacher');
        })->with('classGroups.students')->get();

        return view('admin.teachers', compact('teachers'));
    }

    /**
     * Show teacher's assigned classes (also accessible by admin).
     */
    public function myClasses()
    {
        if (!auth()->user()->hasAnyRole(['teacher', 'admin'])) {
            abort(403);
        }

        // Admin sees all classes, teacher sees only their assigned classes
        if (auth()->user()->hasRole('admin')) {
            $classGroups = ClassGroup::with(['teacher', 'students'])->get();
        } else {
            $classGroups = ClassGroup::where('teacher_id', auth()->id())
                ->with('students')
                ->withCount('students')
                ->get();
        }

        return view('teacher.my-classes', compact('classGroups'));
    }

    /**
     * Show page to manage student assignments (for teachers).
     */
    public function manageStudents()
    {
        if (!auth()->user()->hasRole('teacher')) {
            abort(403);
        }

        $classGroup = ClassGroup::where('teacher_id', auth()->id())->first();

        if (!$classGroup) {
            return redirect()->route('teacher.my-classes')
                ->with('error', 'You are not assigned to any class yet.');
        }

        $students = User::role('student')
            ->orderBy('name')
            ->get();

        return view('teacher.manage-students', compact('classGroup', 'students'));
    }

    /**
     * Show the form for creating a new class group.
     */
    public function create()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('classgroups.create');
    }

    /**
     * Store a newly created class group.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        ClassGroup::create($request->all());

        $this->logActivity(
            'classgroup_created',
            "Created class group '{$request->name}' (Grade: {$request->grade_level})",
            null,
            ['grade_level' => $request->grade_level]
        );

        return redirect()->route('classgroups.index')->with('success', 'Class group created successfully');
    }

    /**
     * Display the specified class group.
     */
    public function show(ClassGroup $classgroup)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'teacher'])) {
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
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('classgroups.edit', compact('classgroup'));
    }

    /**
     * Update the specified class group.
     */
    public function update(Request $request, ClassGroup $classgroup)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|string|max:50',
            'description' => 'nullable|string',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $classgroup->update($request->all());

        $this->logActivity(
            'classgroup_updated',
            "Updated class group '{$classgroup->name}'",
            $classgroup
        );

        return redirect()->route('classgroups.index')->with('success', 'Class group updated successfully');
    }

    /**
     * Remove the specified class group.
     */
    public function destroy(ClassGroup $classgroup)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $classgroup->delete();

        $this->logActivity(
            'classgroup_deleted',
            "Deleted class group '{$classgroup->name}'",
            $classgroup
        );

        return redirect()->route('classgroups.index')->with('success', 'Class group deleted successfully');
    }

    /**
     * Assign students to a class group.
     */
    public function assignStudents(Request $request, ClassGroup $classgroup)
    {
        $user = auth()->user();

        // Admin can assign to any class, teachers can only assign to their own classes
        if ($user->hasRole('admin')) {
            // Admin can assign to any class
        } elseif ($user->hasRole('teacher')) {
            // Teachers can only assign students to their own classes
            if ($classgroup->teacher_id !== $user->id) {
                abort(403);
            }
        } else {
            abort(403);
        }

        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        User::whereIn('id', $request->student_ids)->update([
            'class_group_id' => $classgroup->id,
        ]);

        $this->logActivity(
            'students_assigned_to_class',
            "Assigned " . count($request->student_ids) . " student(s) to class group '{$classgroup->name}'",
            $classgroup,
            ['student_ids' => $request->student_ids]
        );

        return back()->with('success', 'Students assigned to class successfully');
    }

    /**
     * Assign a teacher to a class group.
     */
    public function assignTeacher(Request $request, ClassGroup $classgroup)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $classgroup->update([
            'teacher_id' => $request->teacher_id,
        ]);

        $teacherName = $request->teacher_id ? User::find($request->teacher_id)->name : 'No teacher';
        $this->logActivity(
            'teacher_assigned_to_class',
            "Assigned teacher {$teacherName} to class group '{$classgroup->name}'",
            $classgroup,
            ['teacher_id' => $request->teacher_id]
        );

        return back()->with('success', 'Teacher assigned to class successfully');
    }

    /**
     * Display activity logs (admin only).
     */
    public function activityLog()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $logs = \App\Models\ActivityLog::with('user')
            ->latest()
            ->paginate(50);

        return view('admin.activity-log', compact('logs'));
    }
}
