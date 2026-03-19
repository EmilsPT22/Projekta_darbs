@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        Grade Entry – {{ $internship->name }}
    </h2>

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

        @if(auth()->user()->hasRole('internship_manager'))
        <div class="mb-3">
            <label for="internship_manager_comment" class="form-label">Manager Comment</label>
            <textarea name="internship_manager_comment" id="internship_manager_comment" class="form-control" rows="4" placeholder="Add your feedback or comments here...">{{ old('internship_manager_comment', $entry->internship_manager_comment) }}</textarea>
            @error('internship_manager_comment')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        @endif

        @if(auth()->user()->hasRole('teacher'))
        <div class="mb-3">
            <label for="org_supervisor_comment" class="form-label">Teacher Comment</label>
            <textarea name="org_supervisor_comment" id="org_supervisor_comment" class="form-control" rows="4" placeholder="Add your feedback...">{{ old('org_supervisor_comment', $entry->org_supervisor_comment) }}</textarea>
            @error('org_supervisor_comment')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
        <div class="mb-3">
            <label for="grade" class="form-label">Grade (1-10)</label>
            <input type="number" name="grade" id="grade" class="form-control" min="1" max="10" value="{{ old('grade', $entry->grade) }}" placeholder="Enter grade" required>
            @error('grade')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        @endif

        @if(auth()->user()->hasRole('teacher'))
        <div class="mb-3">
            <label class="form-label">Current Grade</label>
            <div class="form-control-plaintext">
                @if($entry->grade)
                    <span class="badge bg-primary">{{ $entry->grade }}/10</span>
                @else
                    <span class="text-muted">No grade yet</span>
                @endif
            </div>
        </div>
        @endif

        <button type="submit" class="btn btn-primary">
            @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
                Save Grade
            @else
                Save Comment
            @endif
        </button>
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
