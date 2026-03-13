@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $classgroup->name }}</h1>
        <a href="{{ route('classgroups.index') }}" class="btn btn-secondary">Back to Classes</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card bg-dark border-secondary mb-4">
        <div class="card-body">
            <p class="text-muted"><strong>Grade Level:</strong> {{ $classgroup->grade_level }}</p>
            <p>{{ $classgroup->description }}</p>
        </div>
    </div>

    <h3 class="mb-3">Students in this Class ({{ $students->count() }})</h3>

    @if($students->isEmpty())
        <div class="alert alert-info">
            No students assigned to this class yet.
        </div>
    @else
        <div class="list-group">
            @foreach($students as $student)
                <div class="list-group-item bg-dark border-secondary text-light d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $student->name }}</strong>
                        <br>
                        <small class="text-muted">{{ $student->email }}</small>
                    </div>
                    <div>
                        @if($student->hasRole('student'))
                            <span class="badge bg-success">Student</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
        <div class="mt-4">
            <h4>Assign Students to this Class</h4>
            <form action="{{ route('classgroups.assign-students', $classgroup) }}" method="POST" class="mt-3">
                @csrf
                <div class="mb-3">
                    <label for="student_ids" class="form-label">Select Students</label>
                    <select class="form-select" id="student_ids" name="student_ids[]" multiple required style="min-height: 200px;">
                        @foreach(\App\Models\User::role('student')->whereNull('class_group_id')->orWhere('class_group_id', '!=', $classgroup->id)->get() as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }} ({{ $student->email }})
                                @if($student->classGroup)
                                    - Currently in: {{ $student->classGroup->name }}
                                @else
                                    - No class assigned
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple students</small>
                </div>
                <button type="submit" class="btn btn-primary">Assign Selected Students</button>
            </form>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
