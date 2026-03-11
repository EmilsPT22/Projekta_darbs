@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Journal – {{ $internship->name }}</h2>
        <div>
            @if(auth()->user()->hasRole('student'))
                <a href="{{ route('entries.calendar', ['internship' => $internship->id, 'student' => auth()->user()->id]) }}" class="btn btn-primary">
                    Calendar View
                </a>
                <a href="{{ route('entries.create', $internship->id) }}" class="btn btn-success ms-2">
                    + Add Entry
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-hover table-dark">
            <thead>
                <tr>
                    @if(auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher']))
                        <th>Student</th>
                    @endif
                    <th>Date</th>
                    <th>Plan</th>
                    <th>Location</th>
                    <th>Hours</th>
                    <th>Intern Comment</th>
                    @if(auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher']))
                        <th>Admin Comment</th>
                        <th>Grade</th>
                        @if(auth()->user()->hasRole('admin'))
                            <th>Actions</th>
                        @endif
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        @if(auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher']))
                            <td>{{ $entry->user->name }}</td>
                        @endif
                        <td>{{ $entry->date }}</td>
                        <td>{{ $entry->theme->name }}</td>
                        <td>
                            <span class="badge bg-info">{{ $entry->location }}</span>
                        </td>
                        <td>{{ $entry->credit_hours }}</td>
                        <td>{{ $entry->intern_comment ?? '-' }}</td>

                        @if(auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher']))
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
                            @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
                            <td>
                                <a href="{{ route('entries.edit', [$internship->id, $entry->id]) }}" class="btn btn-warning btn-sm">
                                    @if(auth()->user()->hasRole('admin'))
                                        Edit
                                    @else
                                        Grade
                                    @endif
                                </a>
                            </td>
                            @endif
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasAnyRole(['admin', 'internship_manager', 'teacher']) ? '8' : '5' }}" class="text-center text-muted py-4">
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
