@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Student Classes</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary">
            <h5 class="mb-0">All Students</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-dark">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current Class</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->name }}</strong>
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    @if($student->classGroup)
                                        <span class="badge bg-info">{{ $student->classGroup->grade_level }}</span>
                                        <span class="text-muted ms-1">{{ $student->classGroup->name }}</span>
                                    @else
                                        <span class="text-muted">No class assigned</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.edit-grade', $student->id) }}" class="btn btn-warning btn-sm">
                                        {{ $student->classGroup ? 'Change Class' : 'Assign Class' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
