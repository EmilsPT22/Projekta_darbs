@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        @if(auth()->user()->hasRole('admin'))
            Edit Entry – {{ $internship->name }}
        @elseif(auth()->user()->hasRole('teacher'))
            Approve/Reject Entry – {{ $internship->name }}
        @else
            Grade Entry – {{ $internship->name }}
        @endif
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
                    @if($entry->status)
                        <p><strong>Status:</strong> 
                            @if($entry->status === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($entry->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('entries.update', [$internship->id, $entry->id]) }}" method="POST">
        @csrf
        @method('PATCH')

        @if(auth()->user()->hasRole('admin'))
        <div class="mb-3">
            <label for="admin_comment" class="form-label">Admin Comment</label>
            <textarea name="admin_comment" id="admin_comment" class="form-control" rows="4" placeholder="Add your feedback or comments here...">{{ old('admin_comment', $entry->admin_comment) }}</textarea>
            @error('admin_comment')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        @endif

        @if(auth()->user()->hasRole('teacher'))
        <div class="mb-3">
            <label for="org_supervisor_comment" class="form-label">Teacher Comment</label>
            <textarea name="org_supervisor_comment" id="org_supervisor_comment" class="form-control" rows="4" placeholder="Add your feedback or reason for rejection...">{{ old('org_supervisor_comment', $entry->org_supervisor_comment) }}</textarea>
            @error('org_supervisor_comment')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Decision</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="approve" value="approved" {{ old('status', $entry->status) === 'approved' ? 'checked' : '' }}>
                <label class="form-check-label text-success" for="approve">
                    Approve Entry
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="status" id="reject" value="rejected" {{ old('status', $entry->status) === 'rejected' ? 'checked' : '' }}>
                <label class="form-check-label text-danger" for="reject">
                    Reject Entry
                </label>
            </div>
            @error('status')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>
        @endif

        <div class="mb-3">
            <label for="grade" class="form-label">Grade (1-10)</label>
            <input type="number" name="grade" id="grade" class="form-control" min="1" max="10" value="{{ old('grade', $entry->grade) }}" placeholder="Enter grade" required>
            @error('grade')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            @if(auth()->user()->hasRole('admin'))
                Save Changes
            @elseif(auth()->user()->hasRole('teacher'))
                Save Decision
            @else
                Save Grade
            @endif
        </button>
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
