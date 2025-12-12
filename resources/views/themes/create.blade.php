@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Theme to: {{ $internship->name }}</h1>

    <form action="{{ route('themes.store', $internship->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Theme Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Max Hours for this Theme</label>
            <input type="number" name="max_hours" class="form-control" min="1" max="160" required>
            <small class="text-muted">
                Each internship allows only 160 hours in total across all themes.
            </small>
        </div>

        <button class="btn btn-primary">Save Theme</button>
        <a href="{{ route('themes.index', $internship->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
