@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Internships</h2>
        @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
            <a href="{{ route('internships.create') }}" class="btn btn-primary">Create Internship</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($internships->isEmpty())
        <div class="alert alert-info">No internships available.</div>
    @else
        <div class="row">
            @foreach($internships as $internship)
                <div class="col-12 mb-4">
                    <div class="card h-100 bg-dark border-secondary">
                        <div class="card-header border-secondary">
                            <h5 class="mb-0">{{ $internship->name }}</h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text">{{ Str::limit($internship->description, 150) }}</p>
                            <p class="mb-2">
                                <small class="text-muted">
                                    <strong>Duration:</strong> {{ $internship->length }} months<br>
                                    <strong>Period:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}
                                </small>
                            </p>
                        </div>
                        <div class="card-footer border-secondary">
                            @if(auth()->user()->hasRole('student'))
                                <a href="{{ route('internships.show', $internship) }}" class="btn btn-info btn-sm">View Details</a>
                                @if($internship->students->contains(auth()->user()->id))
                                    <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-success btn-sm ms-2">My Journal</a>
                                @endif
                            @elseif(auth()->user()->hasRole('teacher'))
                                <a href="{{ route('internships.show', $internship) }}" class="btn btn-info btn-sm">View Details</a>
                                <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-success btn-sm ms-2">View Journal</a>
                            @elseif(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
                                <a href="{{ route('internships.show', $internship) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-success btn-sm ms-2">View Journal</a>
                                <a href="{{ route('internships.edit', $internship) }}" class="btn btn-warning btn-sm ms-2">Edit</a>
                                <form action="{{ route('internships.destroy', $internship) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ms-1" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
