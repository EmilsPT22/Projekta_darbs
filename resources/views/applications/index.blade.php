@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Applications for {{ $internship->name }}</h2>
        <a href="{{ route('internships.show', $internship->id) }}" class="btn btn-secondary">Back to Internship</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="applicationTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                All Applications ({{ $applications->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                Pending ({{ $applications->where('status', 'pending')->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                Approved ({{ $applications->where('status', 'approved')->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                Rejected ({{ $applications->where('status', 'rejected')->count() }})
            </button>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="applicationTabsContent">
        {{-- All Applications --}}
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            @if($applications->isEmpty())
                <div class="alert alert-info">No applications yet.</div>
            @else
                @include('applications.partials.applications-table', ['applications' => $applications, 'internship' => $internship])
            @endif
        </div>

        {{-- Pending Applications --}}
        <div class="tab-pane fade" id="pending" role="tabpanel">
            @php $pending = $applications->where('status', 'pending'); @endphp
            @if($pending->isEmpty())
                <div class="alert alert-info">No pending applications.</div>
            @else
                @include('applications.partials.applications-table', ['applications' => $pending, 'internship' => $internship])
            @endif
        </div>

        {{-- Approved Applications --}}
        <div class="tab-pane fade" id="approved" role="tabpanel">
            @php $approved = $applications->where('status', 'approved'); @endphp
            @if($approved->isEmpty())
                <div class="alert alert-info">No approved applications.</div>
            @else
                @include('applications.partials.applications-table', ['applications' => $approved, 'internship' => $internship])
            @endif
        </div>

        {{-- Rejected Applications --}}
        <div class="tab-pane fade" id="rejected" role="tabpanel">
            @php $rejected = $applications->where('status', 'rejected'); @endphp
            @if($rejected->isEmpty())
                <div class="alert alert-info">No rejected applications.</div>
            @else
                @include('applications.partials.applications-table', ['applications' => $rejected, 'internship' => $internship])
            @endif
        </div>
    </div>
</div>
@endsection
