@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">User Details</h2>
        <div>
            <a href="{{ route('admin.users.edit-roles', $user->id) }}" class="btn btn-warning btn-sm me-2">
                Manage Roles
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Created:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Current Roles</h5>
                </div>
                <div class="card-body">
                    @if($user->roles->isEmpty())
                        <p class="text-muted">This user has no roles assigned.</p>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($user->roles as $role)
                                <div class="d-flex align-items-center bg-primary rounded px-3 py-2">
                                    <span class="me-2">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</span>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.remove-role', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="role" value="{{ $role->name }}">
                                            <button type="submit" class="btn btn-sm btn-link text-white p-0" onclick="return confirm('Remove this role?')">
                                                &times;
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($user->hasRole('student'))
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Student Class</h5>
                </div>
                <div class="card-body">
                    @if($user->classGroup)
                        <p><strong>Current Class:</strong> <span class="text-info">{{ $user->classGroup->grade_level }}</span> ({{ $user->classGroup->name }})</p>
                    @else
                        <p class="text-muted">No class assigned yet.</p>
                    @endif

                    @if(auth()->user()->hasAnyRole(['admin', 'teacher']))
                        <a href="{{ route('admin.users.edit-grade', $user->id) }}" class="btn btn-warning btn-sm">
                            {{ $user->classGroup ? 'Change Class' : 'Assign Class' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
