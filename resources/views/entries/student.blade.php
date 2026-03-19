@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $student->name }} – Journal Entries</h2>
        <a href="{{ route('entries.calendar', ['internship' => $internship->id, 'student' => $student->id]) }}" class="btn btn-primary">
            Calendar View
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($entries->isEmpty())
        <div class="alert alert-info">No entries found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-dark">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Plan</th>
                        <th>Location</th>
                        <th>Hours</th>
                        <th>Intern Comment</th>
                        <th>Teacher Comment</th>
                        <th>Manager Comment</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                        <tr>
                            <td>{{ $entry->date }}</td>
                            <td>{{ $entry->theme->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $entry->location }}</span>
                            </td>
                            <td>{{ $entry->credit_hours }}</td>
                            <td>{{ $entry->intern_comment ?? '-' }}</td>
                            <td>
                                @if($entry->org_supervisor_comment)
                                    <small>{{ Str::limit($entry->org_supervisor_comment, 50) }}</small>
                                @else
                                    <span class="text-muted">No comment</span>
                                @endif
                            </td>
                            <td>
                                @if($entry->internship_manager_comment)
                                    <small>{{ Str::limit($entry->internship_manager_comment, 50) }}</small>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <a href="{{ route('internships.show', $internship->id) }}" class="btn btn-primary mt-3">Back to Internship</a>
</div>
@endsection


