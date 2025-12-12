@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Themes for Internship: {{ $internship->name }}</h1>

    <a href="{{ route('themes.create', $internship->id) }}" class="btn btn-primary mb-3">Add Theme</a>

    <ul class="list-group">
        @foreach($themes as $theme)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $theme->name }}</strong><br>
                    <small>{{ $theme->max_hours }} hours available</small>
                </div>

                <div>
                    <a href="{{ route('themes.edit', [$internship->id, $theme->id]) }}"
                       class="btn btn-warning btn-sm">Edit</a>

                    <form action="{{ route('themes.destroy', [$internship->id, $theme->id]) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this theme?')" class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('internships.show', $internship->id) }}" class="btn btn-secondary mt-3">
        Back to Internship
    </a>
</div>
@endsection
