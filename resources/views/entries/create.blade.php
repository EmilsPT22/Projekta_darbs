@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add Entry — {{ $internship->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('entries.store', $internship->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Day Plan (Theme)</label>
            <select name="theme_id" class="form-select" required>
                <option value="" disabled selected>Select theme</option>
                @foreach($themes as $theme)
                    <option value="{{ $theme->id }}">
                        {{ $theme->name }}
                        ({{ $theme->remainingHoursForUser(auth()->id()) }} h left)
                    </option>
                @endforeach
                @if($themes->isEmpty())
                    <option value="" disabled>No themes available with remaining hours</option>
                @endif
            </select>
            @if($themes->isEmpty())
                <small class="text-muted">
                    You have used all your assigned hours, or no themes have been assigned to you yet.
                    Contact your administrator.
                </small>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Date</label>
            <input type="date"
                   name="date"
                   class="form-control"
                   min="{{ $internship->start_date }}"
                   max="{{ $internship->end_date }}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Location</label>
            <select name="location" class="form-select" required>
                <option value="remote">Remote</option>
                <option value="on-site">On-site</option>
                <option value="mixed">Mixed</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Time From</label>
            <input type="time" name="time_from" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Time To</label>
            <input type="time" name="time_to" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Credit Hours</label>
            <input type="number"
                   name="credit_hours"
                   class="form-control"
                   min="1"
                   max="12"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Intern Comment</label>
            <textarea name="intern_comment" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
