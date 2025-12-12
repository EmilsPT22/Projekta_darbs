@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Entry — {{ $internship->name }}</h2>

    @if ($errors->any())
        <ul class="list-group mb-4 text-danger">
            @foreach ($errors->all() as $error)
                <li class="list-group-item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('entries.store', $internship->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Day Plan (Theme)</label>
            <select name="theme_id" class="form-control" required>
                <option value="" disabled selected>Select theme</option>
                @foreach($themes as $theme)
                    <option value="{{ $theme->id }}">
                        {{ $theme->name }} ({{ $theme->max_hours }} h)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Date</label>
            <input type="date"
                   name="date"
                   class="form-control"
                   min="{{ $internship->start_date }}"
                   max="{{ $internship->end_date }}"
                   required>
        </div>

        <div class="mb-3">
            <label>Location</label>
            <select name="location" class="form-control" required>
                <option value="remote">Remote</option>
                <option value="on-site">On-site</option>
                <option value="mixed">Mixed</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Time From</label>
            <input type="time" name="time_from" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Time To</label>
            <input type="time" name="time_to" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Credit Hours</label>
            <input type="number"
                   name="credit_hours"
                   class="form-control"
                   min="1"
                   max="12"
                   required>
        </div>

        <div class="mb-3">
            <label>Intern Comment</label>
            <textarea name="intern_comment" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
