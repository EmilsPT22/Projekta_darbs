@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">My Application</h2>
        <a href="{{ route('internships.show', $internship->id) }}" class="btn btn-secondary">Back to Internship</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Internship Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $internship->name }}</p>
                    <p><strong>Duration:</strong> {{ $internship->length }} months</p>
                    <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }}</p>
                    <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">My Application Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Applied on:</strong> {{ $application->created_at->format('d/m/Y H:i') }}</p>
                    
                    @if($application->cover_letter)
                        <div class="mb-3">
                            <h6>Cover Letter</h6>
                            <p class="text-muted">{{ $application->cover_letter }}</p>
                        </div>
                    @endif

                    <div class="mb-3">
                        <h6>Motivation</h6>
                        <p class="text-muted">{{ $application->motivation }}</p>
                    </div>

                    @if($application->phone)
                        <div class="mb-3">
                            <h6>Phone</h6>
                            <p class="text-muted">{{ $application->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Application Status</h5>
                </div>
                <div class="card-body">
                    @if($application->status === 'pending')
                        <span class="badge bg-warning mb-2">Pending Review</span>
                        <p class="text-muted small mt-2">Your application is being reviewed by the internship manager.</p>
                    @elseif($application->status === 'approved')
                        <span class="badge bg-success mb-2">Approved</span>
                        <p class="text-success small mt-2">Congratulations! You have been accepted for this internship.</p>
                    @elseif($application->status === 'rejected')
                        <span class="badge bg-danger mb-2">Rejected</span>
                        @if($application->manager_comment)
                            <hr>
                            <h6>Reason for rejection:</h6>
                            <p class="text-muted small">{{ $application->manager_comment }}</p>
                        @else
                            <p class="text-muted small mt-2">Unfortunately, your application was not approved.</p>
                        @endif
                    @endif

                    @if($application->manager_comment && $application->status !== 'rejected')
                        <hr>
                        <h6>Manager's Comment:</h6>
                        <p class="text-muted small">{{ $application->manager_comment }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
