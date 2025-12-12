@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Journal – {{ $internship->name }}</h2>

    @if(auth()->user()->role === 'student')
        <a href="{{ route('entries.create', $internship->id) }}" class="btn btn-success mb-3">Add Entry</a>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Plan</th>
                <th>Location</th>
                <th>Hours</th>
                <th>Intern Comment</th>
                @if(auth()->user()->role === 'admin')
                    <th>Admin Comment</th>
                    <th>Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @foreach($entries as $entry)
                <tr>
                    <td>{{ $entry->date }}</td>
                    <td>{{ $entry->theme->name }}</td>
                    <td>{{ $entry->location }}</td>
                    <td>{{ $entry->credit_hours }}</td>
                    <td>{{ $entry->intern_comment }}</td>

                    @if(auth()->user()->role === 'admin')
                        <td>{{ $entry->org_supervisor_comment }}</td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
