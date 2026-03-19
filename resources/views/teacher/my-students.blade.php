@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">My Students</h2>
        <a href="{{ route('internships.index') }}" class="btn btn-secondary">Back to Internships</a>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Students in Your Classes</h5>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                <p class="text-muted">You don't have any students assigned yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-dark">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Class</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $student)
                                <tr>
                                    <td><strong>{{ $student->name }}</strong></td>
                                    <td>{{ $student->email }}</td>
                                    <td>
                                        @if($student->classGroup)
                                            <span class="badge bg-info">{{ $student->classGroup->grade_level }}</span>
                                            <span class="text-muted ms-1">{{ $student->classGroup->name }}</span>
                                        @else
                                            <span class="text-muted">No class</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $student->id) }}" class="btn btn-info btn-sm">
                                            View Profile
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
