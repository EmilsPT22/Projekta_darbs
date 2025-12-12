@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Theme: {{ $theme->name }}</h1>

    <form action="{{ route('themes.update', [$internship->id, $theme->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Theme Name</label>
            <input type="text" name="name" class="form-control" value="{{ $theme->name }}" required>
        </div>

        <div class="mb-3">
            <label>Max Hours</label>
            <input type="number" name="max_hours" class="form-control" value="{{ $theme->max_hours }}" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('themes.index', $internship->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
