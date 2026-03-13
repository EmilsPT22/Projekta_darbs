@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Class/Grade</h1>

    <div class="card bg-dark border-secondary">
        <div class="card-body">
            <form action="{{ route('classgroups.update', $classgroup) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Class Name</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $classgroup->name) }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="grade_level" class="form-label">Grade Level</label>
                    <input type="text" class="form-control" id="grade_level" name="grade_level" 
                           value="{{ old('grade_level', $classgroup->grade_level) }}" required>
                    @error('grade_level')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description (optional)</label>
                    <textarea class="form-control" id="description" name="description" 
                              rows="3">{{ old('description', $classgroup->description) }}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Class</button>
                    <a href="{{ route('classgroups.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
