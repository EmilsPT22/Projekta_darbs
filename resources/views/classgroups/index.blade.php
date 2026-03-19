@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Class/Grade Management</h2>
        <div>
            @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('classgroups.create') }}" class="btn btn-primary me-2">Create New Class</a>
            @endif
            <a href="{{ route('internships.index') }}" class="btn btn-secondary">Back to Internships</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($classGroups->isEmpty())
        <div class="alert alert-info">No class groups created yet. Create your first class to organize students.</div>
    @else
        <div class="row">
            @foreach($classGroups as $classGroup)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card bg-dark border-secondary h-100">
                        <div class="card-header border-secondary d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $classGroup->name }}</h5>
                            <span class="badge bg-info">{{ $classGroup->students_count }} students</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ $classGroup->description ?? 'No description' }}</p>
                            <p class="text-muted"><strong>Grade Level:</strong> {{ $classGroup->grade_level }}</p>
                            <p class="text-muted">
                                <strong>Teacher:</strong>
                                @if($classGroup->teacher)
                                    <span class="text-info">{{ $classGroup->teacher->name }}</span>
                                @else
                                    <span class="text-warning">No teacher assigned</span>
                                @endif
                            </p>
                        </div>
                        <div class="card-footer border-secondary">
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('classgroups.show', $classGroup) }}" class="btn btn-sm btn-info">
                                        View Students
                                    </a>
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('classgroups.edit', $classGroup) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                        <form action="{{ route('classgroups.destroy', $classGroup) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Delete this class group? Students will not be deleted.')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                @if(auth()->user()->hasRole('admin'))
                                <form action="{{ route('classgroups.assign-teacher', $classGroup) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <select name="teacher_id" class="form-select form-select-sm" style="width: 200px;">
                                        <option value="">-- Assign Teacher --</option>
                                        @foreach(\App\Models\User::role('teacher')->orderBy('name')->get() as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $classGroup->teacher_id == $teacher->id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
