@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Internship Details</h1>

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('themes.index', $internship->id) }}" class="btn btn-secondary mb-3">
            Manage Themes
        </a>
    @endif

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Name:</strong> {{ $internship->name }}</li>
        <li class="list-group-item"><strong>Description:</strong> {{ $internship->description }}</li>
        <li class="list-group-item"><strong>Length:</strong> {{ $internship->length }} months</li>
        <li class="list-group-item"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }}</li>
        <li class="list-group-item"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</li>
    </ul>

    {{-- ADMIN ONLY --}}
    @if(auth()->user()->role === 'admin')

        <h2 class="mb-3">Added Students</h2>

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

                            <form
                                action="{{ route('internships.removeStudent', ['internship' => $internship->id, 'id' => $student->id]) }}"
                                method="POST"
                                style="display:inline;"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Remove
                                </button>
                            </form>
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

@if(auth()->user()->role === 'admin')
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
