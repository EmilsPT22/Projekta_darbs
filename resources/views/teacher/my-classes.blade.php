@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ auth()->user()->hasRole('admin') ? 'All Classes' : 'My Classes' }}</h2>
        <div>
            @if(auth()->user()->hasRole('teacher'))
                <a href="{{ route('teacher.manage-students') }}" class="btn btn-primary me-2">
                    Manage Students
                </a>
            @endif
            <a href="{{ route('internships.index') }}" class="btn btn-secondary">Back to Internships</a>
        </div>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary">
            <h5 class="mb-0">{{ auth()->user()->hasRole('admin') ? 'All Classes in the System' : 'Classes You Teach' }}</h5>
        </div>
        <div class="card-body">
            @if($classGroups->isEmpty())
                <p class="text-muted">You are not assigned to any classes yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Class Name</th>
                                <th>Grade Level</th>
                                @if(auth()->user()->hasRole('admin'))
                                    <th>Teacher</th>
                                @endif
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($classGroups as $class)
                                <tr>
                                    <td><strong>{{ $class->name }}</strong></td>
                                    <td>{{ $class->grade_level }}</td>
                                    @if(auth()->user()->hasRole('admin'))
                                        <td>{{ $class->teacher ? $class->teacher->name : 'No teacher assigned' }}</td>
                                    @endif
                                    <td>{{ $class->students_count ?? $class->students->count() }} students</td>
                                    <td>
                                        <a href="{{ route('admin.students-grade', ['class_group_id' => $class->id]) }}" class="btn btn-info btn-sm">
                                            View Students
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
