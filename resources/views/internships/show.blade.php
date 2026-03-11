@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">{{ $internship->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
        <a href="{{ route('themes.index', $internship->id) }}" class="btn btn-secondary mb-3">
            Manage Themes
        </a>
        <a href="{{ route('applications.index', $internship->id) }}" class="btn btn-info mb-3 ms-2">
            View Applications
        </a>
    @endif

    @if(auth()->user()->hasAnyRole(['admin', 'teacher']))
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-success mb-3">
            View All Journal Entries
        </a>
    @endif

    @if(auth()->user()->hasRole('student'))
        @if($internship->students->contains(auth()->id()))
            <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-success mb-3">
                Go to My Journal
            </a>
        @else
            @php
                $application = \App\Models\InternshipApplication::where('internship_id', $internship->id)
                    ->where('user_id', auth()->id())
                    ->first();
            @endphp
            @if($application)
                @if($application->status === 'pending')
                    <a href="{{ route('applications.student-view', $internship->id) }}" class="btn btn-warning mb-3">
                        Application Submitted (Pending)
                    </a>
                @elseif($application->status === 'approved')
                    <a href="{{ route('applications.student-view', $internship->id) }}" class="btn btn-success mb-3">
                        Application Approved
                    </a>
                @elseif($application->status === 'rejected')
                    <a href="{{ route('applications.student-view', $internship->id) }}" class="btn btn-danger mb-3">
                        Application Rejected
                    </a>
                @endif
            @else
                <a href="{{ route('applications.create', $internship->id) }}" class="btn btn-primary mb-3">
                    Apply Now
                </a>
            @endif
        @endif
    @endif

    <div class="card bg-dark border-secondary mb-4">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Description</h5>
        </div>
        <div class="card-body">
            <p class="card-text">{{ $internship->description }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Duration</h6>
                    <h4>{{ $internship->length }} months</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">Start Date</h6>
                    <h4>{{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted">End Date</h6>
                    <h4>{{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- ADMIN, INTERNSHIP MANAGER & TEACHER: View Students --}}
    @if(auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher']))

        <h2 class="mb-3">Students</h2>

        @if($addedStudents->isEmpty())
            <p>No students added yet.</p>
        @else
            <ul class="list-group mb-4">
                @foreach($addedStudents as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $student->name }} ({{ $student->email }})

                        <div class="d-flex gap-2">
                            <a
                                href="{{ route('entries.student', ['internship' => $internship->id, 'student' => $student->id]) }}"
                                class="btn btn-secondary btn-sm"
                            >
                                View Journal
                            </a>

                            @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
                            <form
                                action="{{ route('internships.removeStudent', ['internship' => $internship->id, 'id' => $student->id]) }}"
                                method="POST"
                                style="display:inline;"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Remove this student?')">
                                    Remove
                                </button>
                            </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

    @endif

    <a href="{{ route('internships.index') }}" class="btn btn-primary">
        Back to Internships
    </a>
</div>

@if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
    <h2 class="mb-3">Add Students</h2>

    @if($users->isEmpty())
        <p>All students have been added.</p>
    @else
        <ul class="list-group mb-4">
            @foreach($users as $user)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $user->name }} ({{ $user->email }})

                    <form
                        action="{{ route('internships.addStudent', ['internship' => $internship->id, 'id' => $user->id]) }}"
                        method="POST"
                    >
                        @csrf
                        <button class="btn btn-success btn-sm">
                            Add
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
@endif

@endsection



@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
