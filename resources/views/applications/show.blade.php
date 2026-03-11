@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Application Details</h2>
        <a href="{{ route('applications.index', $internship->id) }}" class="btn btn-secondary">Back to Applications</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Student Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $application->student->name }}</p>
                    <p><strong>Email:</strong> {{ $application->student->email }}</p>
                    <p><strong>Phone:</strong> {{ $application->phone ?? 'Not provided' }}</p>
                    <p><strong>Applied:</strong> {{ $application->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Application Details</h5>
                </div>
                <div class="card-body">
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

                    @if($application->cv_path)
                        <div class="mb-3">
                            <h6>CV</h6>
                            <a href="{{ Storage::url($application->cv_path) }}" target="_blank" class="btn btn-outline-light btn-sm">
                                Download CV
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-dark border-secondary mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    @if($application->status === 'pending')
                        <span class="badge bg-warning mb-2">Pending Review</span>
                    @elseif($application->status === 'approved')
                        <span class="badge bg-success mb-2">Approved</span>
                    @else
                        <span class="badge bg-danger mb-2">Rejected</span>
                    @endif

                    @if($application->manager_comment)
                        <hr>
                        <h6>Manager's Comment:</h6>
                        <p class="text-muted small">{{ $application->manager_comment }}</p>
                    @endif
                </div>
            </div>

            @if($application->status === 'pending')
            <div class="card bg-dark border-secondary">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('applications.approve', [$internship->id, $application->id]) }}" method="POST" class="mb-2">
                        @csrf
                        <div class="mb-2">
                            <textarea name="manager_comment" class="form-control" rows="2" placeholder="Add a comment (optional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm w-100">Approve & Add Student</button>
                    </form>

                    <button type="button" class="btn btn-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        Reject Application
                    </button>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark border-secondary">
                        <form action="{{ route('applications.reject', [$internship->id, $application->id]) }}" method="POST">
                            @csrf
                            <div class="modal-header border-secondary">
                                <h5 class="modal-title">Reject Application</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to reject this application?</p>
                                <div class="mb-3">
                                    <label class="form-label">Reason (optional)</label>
                                    <textarea name="manager_comment" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-secondary">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
