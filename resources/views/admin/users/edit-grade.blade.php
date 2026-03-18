@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Change Class for {{ $user->name }}</h2>
        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Back to User</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary">
        <div class="card-body">
            <form action="{{ route('admin.users.update-grade', $user->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="class_group_id" class="form-label">Class/Grade</label>
                    <select class="form-select" id="class_group_id" name="class_group_id">
                        <option value="">-- No Class Assigned --</option>
                        @foreach($classGroups as $classGroup)
                            <option value="{{ $classGroup->id }}"
                                    {{ $user->class_group_id == $classGroup->id ? 'selected' : '' }}>
                                {{ $classGroup->grade_level }} - {{ $classGroup->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Select the class this student belongs to (e.g., 1st Grade - A).</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Class</button>
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
