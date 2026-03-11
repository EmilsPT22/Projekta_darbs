@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Roles - {{ $user->name }}</h2>
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Back to User Details</a>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Assign Roles</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update-roles', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <p class="text-muted">Select one or more roles to assign to this user.</p>
                </div>

                <div class="mb-3">
                    @foreach($roles as $role)
                        <div class="form-check mb-2">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="roles[]" 
                                value="{{ $role->name }}" 
                                id="role-{{ $role->id }}"
                                {{ $user->hasRole($role->name) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="role-{{ $role->id }}">
                                <strong>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</strong>
                                <small class="text-muted d-block">
                                    @if($role->name === 'admin')
                                        Full access to everything - highest level
                                    @elseif($role->name === 'internship_manager')
                                        Can manage internships, students, and grade entries
                                    @elseif($role->name === 'teacher')
                                        Can view all internships and student journals (read-only)
                                    @elseif($role->name === 'student')
                                        Can apply to internships and submit journal entries
                                    @endif
                                </small>
                            </label>
                        </div>
                    @endforeach
                </div>

                @if($errors->has('roles'))
                    <div class="text-danger mb-3">{{ $errors->first('roles') }}</div>
                @endif

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Roles</button>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    @if($user->roles->isNotEmpty())
    <div class="card bg-dark border-secondary mt-4">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Current Roles</h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                @foreach($user->roles as $role)
                    <span class="badge bg-primary px-3 py-2">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
