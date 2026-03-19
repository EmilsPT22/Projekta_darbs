@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Teachers & Classes</h2>
        <a href="{{ route('classgroups.index') }}" class="btn btn-secondary">Back to Classes</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($teachers->isEmpty())
        <div class="alert alert-info">No teachers found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Teacher Name</th>
                        <th>Email</th>
                        <th>Assigned Classes</th>
                        <th>Total Students</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $teacher)
                        <tr>
                            <td><strong>{{ $teacher->name }}</strong></td>
                            <td>{{ $teacher->email }}</td>
                            <td>
                                @if($teacher->classGroups->isEmpty())
                                    <span class="text-muted">No classes assigned</span>
                                @else
                                    @foreach($teacher->classGroups as $class)
                                        <span class="badge bg-info me-1">
                                            {{ $class->name }} ({{ $class->grade_level }})
                                        </span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                {{ $teacher->classGroups->sum(function($c) { return $c->students->count(); }) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
