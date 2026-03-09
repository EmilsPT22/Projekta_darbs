@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Journal – {{ $internship->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(auth()->user()->hasRole('student'))
        <a href="{{ route('entries.create', $internship->id) }}" class="btn btn-success mb-3">Add Entry</a>
    @endif

    <div class="table-responsive">
        <table class="table table-hover table-dark">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Plan</th>
                    <th>Location</th>
                    <th>Hours</th>
                    <th>Intern Comment</th>
                    @if(auth()->user()->hasRole('admin'))
                        <th>Admin Comment</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td>{{ $entry->date }}</td>
                        <td>{{ $entry->theme->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $entry->location }}</span>
                        </td>
                        <td>{{ $entry->credit_hours }}</td>
                        <td>{{ $entry->intern_comment ?? '-' }}</td>

                        @if(auth()->user()->hasRole('admin'))
                            <td>
                                @if($entry->admin_comment)
                                    <small>{{ Str::limit($entry->admin_comment, 50) }}</small>
                                @else
                                    <span class="text-muted">No comment</span>
                                @endif
                            </td>
                            <td>
                                @if($entry->grade)
                                    <span class="badge bg-primary">{{ $entry->grade }}/10</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('entries.edit', [$internship->id, $entry->id]) }}" class="btn btn-warning btn-sm">
                                    Edit
                                </a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasRole('admin') ? '8' : '5' }}" class="text-center text-muted py-4">
                            No entries yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <a href="{{ route('internships.index') }}" class="btn btn-secondary">Back to Internships</a>
</div>
@endsection
