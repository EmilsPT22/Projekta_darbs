@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Activity Log</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to User Management</a>
    </div>

    <div class="card bg-dark border-secondary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th style="width: 150px;">Date & Time</th>
                            <th style="width: 150px;">User</th>
                            <th>Action</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($log->user)
                                        <span class="text-info">{{ $log->user->name }}</span>
                                        <br>
                                        <small class="text-muted">{{ $log->user->getRoleNames()->join(', ') }}</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <code>{{ $log->action }}</code>
                                </td>
                                <td>{{ $log->description }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No activity logged yet
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
