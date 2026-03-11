@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Apply for {{ $internship->name }}</h2>

    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Internship Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Duration:</strong> {{ $internship->length }} months</p>
            <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</p>
        </div>
    </div>

    <form action="{{ route('applications.store', $internship->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="+371 20000000">
            @error('phone')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="cover_letter" class="form-label">Cover Letter (Optional)</label>
            <textarea name="cover_letter" id="cover_letter" class="form-control" rows="4" placeholder="Tell us about yourself...">{{ old('cover_letter') }}</textarea>
            <small class="text-muted">Max 2000 characters</small>
            @error('cover_letter')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="motivation" class="form-label">Motivation <span class="text-danger">*</span></label>
            <textarea name="motivation" id="motivation" class="form-control" rows="5" placeholder="Why do you want to join this internship? What do you hope to learn?">{{ old('motivation') }}</textarea>
            <small class="text-muted">Max 2000 characters</small>
            @error('motivation')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Submit Application</button>
            <a href="{{ route('internships.show', $internship->id) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
