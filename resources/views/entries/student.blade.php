@extends('layouts.app')

@section('content')
<div class="container">

    <h2>{{ $student->name }} – Journal Entries</h2>
    <h5 class="text-muted">{{ $internship->name }}</h5>

    @if($entries->isEmpty())
        <p>No entries found.</p>
    @else
        <ul class="list-group">
            @foreach($entries as $entry)
                <li class="list-group-item">
                    <strong>{{ $entry->date }}</strong>
                    <br>
                    Plan: {{ $entry->theme->name }}
                    <br>
                    Hours: {{ $entry->credit_hours }}
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('internships.show', $internship->id) }}" class="btn btn-primary mt-3">Back</a>

</div>
@endsection


