@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Internships</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->role === 'admin')
        <a href="{{ route('internships.create') }}" class="btn btn-primary mb-3">Create Internship</a>
    @endif

    <ul class="list-group">
        @foreach($internships as $internship)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $internship->name }}</strong><br>
                    <small>{{ $internship->start_date }} to {{ $internship->end_date }}</small>
                </div>

                <div>
                    <a href="{{ route('internships.show', $internship) }}" class="btn btn-info btn-sm">View</a>

                    @if(auth()->user()->role === 'student')
                        @if($internship->students->contains(auth()->user()->id))
                            <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-success btn-sm">My Journal</a>
                        @endif
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('internships.edit', $internship) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('internships.destroy', $internship) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
