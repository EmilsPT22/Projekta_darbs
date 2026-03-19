@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Students - {{ $classGroup->name }}</h2>
        <a href="{{ route('teacher.my-classes') }}" class="btn btn-secondary">Back to My Classes</a>
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

    <div class="card bg-dark border-secondary">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Assign Students to {{ $classGroup->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('classgroups.assign-students', $classGroup) }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label mb-0">Select Students:</label>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-info me-2" onclick="selectAll()">
                                Select All
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="deselectAll()">
                                Deselect All
                            </button>
                        </div>
                    </div>
                    
                    <div class="border border-secondary rounded p-3" style="max-height: 400px; overflow-y: auto;">
                        <div class="row">
                            @foreach($students as $student)
                                <div class="col-md-4 col-lg-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input student-checkbox" type="checkbox" 
                                               name="student_ids[]" value="{{ $student->id }}" 
                                               id="student_{{ $student->id }}"
                                               {{ $classGroup->students->contains($student) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="student_{{ $student->id }}">
                                            {{ $student->name }}
                                            @if($classGroup->students->contains($student))
                                                <span class="badge bg-success ms-1">Assigned</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('student_ids')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <span id="selectedCount">0</span> student(s) selected - Save Changes
                    </button>
                    <a href="{{ route('teacher.my-classes') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card bg-dark border-secondary mt-4">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Currently Assigned Students ({{ $classGroup->students->count() }})</h5>
        </div>
        <div class="card-body">
            @if($classGroup->students->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($classGroup->students as $student)
                        <li class="list-group-item bg-transparent text-light d-flex justify-content-between align-items-center">
                            {{ $student->name }}
                            <span class="text-muted small">{{ $student->email }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mb-0">No students assigned to this class yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateCount() {
        const count = $('.student-checkbox:checked').length;
        $('#selectedCount').text(count);
    }

    function selectAll() {
        $('.student-checkbox').prop('checked', true);
        updateCount();
    }

    function deselectAll() {
        $('.student-checkbox').prop('checked', false);
        updateCount();
    }

    $(document).ready(function() {
        $('.student-checkbox').change(function() {
            updateCount();
        });
        updateCount();
    });
</script>
@endsection
