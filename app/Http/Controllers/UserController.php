<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClassGroup;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users with their roles (Admin only)
     */
    public function index(Request $request)
    {
        // Only admin can access this page
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $query = User::with('roles');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show user details (Admin only)
     */
    public function show(User $user)
    {
        // Only admin can access this page
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show edit role form (Admin only)
     */
    public function editRoles(User $user)
    {
        // Only admin can access this page
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $roles = Role::all();

        return view('admin.users.edit-roles', compact('user', 'roles'));
    }

    /**
     * Update user roles (Admin only)
     */
    public function updateRoles(Request $request, User $user)
    {
        // Only admin can access this page
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
        ]);

        // Sync roles (remove all existing and add new ones)
        $user->syncRoles($request->roles ?? []);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'User roles updated successfully.');
    }

    /**
     * Remove a role from user (Admin only)
     */
    public function removeRole(Request $request, User $user)
    {
        // Only admin can access this page
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->removeRole($request->role);

        return back()->with('success', 'Role removed successfully.');
    }

    /**
     * Show edit grade form (Admin/Teacher only)
     */
    public function editGrade(User $user)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'teacher'])) {
            abort(403);
        }

        $classGroups = ClassGroup::orderBy('grade_level')->get();

        return view('admin.users.edit-grade', compact('user', 'classGroups'));
    }

    /**
     * Update user grade (Admin/Teacher only)
     */
    public function updateGrade(Request $request, User $user)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'teacher'])) {
            abort(403);
        }

        $request->validate([
            'class_group_id' => 'nullable|exists:class_groups,id',
        ]);

        $user->update([
            'class_group_id' => $request->class_group_id,
        ]);

        // Redirect based on user role
        if (auth()->user()->hasRole('teacher')) {
            return redirect()->route('admin.students-grade')
                ->with('success', 'Grade updated successfully.');
        }

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Show all students for grade management (Admin/Teacher only)
     */
    public function studentsForGrade(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'teacher'])) {
            abort(403);
        }

        $query = User::role('student')->with('classGroup');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by specific class if provided
        if ($request->filled('class_group_id')) {
            $query->where('class_group_id', $request->class_group_id);
        }

        // Teachers can only see students from their assigned classes
        if (auth()->user()->hasRole('teacher')) {
            $query->whereHas('classGroup', function($q) {
                $q->where('teacher_id', auth()->id());
            });
        }

        $students = $query->orderBy('name')->get();

        // Get class groups for filter dropdown (only teacher's classes for teachers)
        if (auth()->user()->hasRole('teacher')) {
            $classGroups = ClassGroup::where('teacher_id', auth()->id())->get();
        } else {
            $classGroups = ClassGroup::all();
        }

        return view('admin.users.students-for-grade', compact('students', 'classGroups'));
    }

    /**
     * Show teacher's assigned students (also accessible by admin).
     */
    public function myStudents()
    {
        if (!auth()->user()->hasAnyRole(['teacher', 'admin'])) {
            abort(403);
        }

        // Admin sees all students, teacher sees only students in their assigned classes
        if (auth()->user()->hasRole('admin')) {
            $students = User::role('student')
                ->with('classGroup')
                ->orderBy('name')
                ->get();
        } else {
            $students = User::role('student')
                ->whereHas('classGroup', function($q) {
                    $q->where('teacher_id', auth()->id());
                })
                ->with('classGroup')
                ->orderBy('name')
                ->get();
        }

        return view('teacher.my-students', compact('students'));
    }
}
