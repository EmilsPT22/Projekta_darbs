<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of all users with their roles (Admin only)
     */
    public function index()
    {
        // Only admin can access this page
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $users = User::with('roles')->orderBy('created_at', 'desc')->get();

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
}
