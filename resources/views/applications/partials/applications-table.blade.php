<div class="table-responsive">
    <table class="table table-hover table-dark">
        <thead>
            <tr>
                <th>Student</th>
                <th>Applied Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($applications as $application)
                <tr>
                    <td>
                        <strong>{{ $application->student->name }}</strong><br>
                        <small class="text-muted">{{ $application->student->email }}</small>
                    </td>
                    <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($application->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($application->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('applications.show', [$internship->id, $application->id]) }}" class="btn btn-info btn-sm">
                            View Details
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
