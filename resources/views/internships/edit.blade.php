@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Edit Internship</h1>

    @if ($errors->any())
        <ul class="list-group mb-4 text-danger">
            @foreach ($errors->all() as $error)
                <li class="list-group-item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('internships.update', $internship) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" name="name" value="{{ old('name', $internship->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Description:</label>
            <textarea name="description" class="form-control" required>{{ old('description', $internship->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Length (months):</label>
            <input type="number" name="length" value="{{ old('length', $internship->length) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Start Date:</label>
            <input type="date" name="start_date" value="{{ old('start_date', $internship->start_date) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>End Date:</label>
            <input type="date" name="end_date" value="{{ old('end_date', $internship->end_date) }}" class="form-control" required>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
