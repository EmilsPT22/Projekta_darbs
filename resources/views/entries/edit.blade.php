@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add Feedback – {{ $internship->name }}</h2>

    <div class="card mb-4 bg-dark border-secondary">
        <div class="card-header border-secondary">
            <strong>Entry Details</strong>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Date:</strong> {{ $entry->date }}</p>
                    <p><strong>Location:</strong> {{ $entry->location }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Time:</strong> {{ $entry->time_from }} - {{ $entry->time_to }}</p>
                    <p><strong>Credit Hours:</strong> {{ $entry->credit_hours }}</p>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <p><strong>Plan:</strong> {{ $entry->theme->name }}</p>
                    <p><strong>Intern Comment:</strong> {{ $entry->intern_comment ?? 'No comment' }}</p>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('entries.update', [$internship->id, $entry->id]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="admin_comment" class="form-label">Admin Comment</label>
            <textarea name="admin_comment" id="admin_comment" class="form-control" rows="4" placeholder="Add your feedback or comments here...">{{ old('admin_comment', $entry->admin_comment) }}</textarea>
            @error('admin_comment')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="grade" class="form-label">Grade (1-10)</label>
            <input type="number" name="grade" id="grade" class="form-control" min="1" max="10" value="{{ old('grade', $entry->grade) }}" placeholder="Optional grade">
            @error('grade')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Save Feedback</button>
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
