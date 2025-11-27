<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internship Details</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Internship Details</h1>

        <ul class="list-group mb-4">
            <li class="list-group-item"><strong>Name:</strong> {{ $internship->name }}</li>
            <li class="list-group-item"><strong>Description:</strong> {{ $internship->description }}</li>
            <li class="list-group-item"><strong>Length:</strong> {{ $internship->length }} months</li>
            <li class="list-group-item"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }}</li>
            <li class="list-group-item"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</li>
        </ul>

        <h2 class="mb-3">Added Students</h2>
        @if($addedStudents->isEmpty())
            <p>No students added yet.</p>
        @else
            <ul class="list-group mb-4">
                @foreach($addedStudents as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $student->name }} ({{ $student->email }})
                        <form action="{{ route('internships.removeStudent', ['internship' => $internship->id, 'id' => $student->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <h2 class="mb-3">Add Students</h2>
        @if($users->isEmpty())
            <p>All students have been added.</p>
        @else
            <ul class="list-group mb-4">
                @foreach($users as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $user->name }} ({{ $user->email }})
                        <form action="{{ route('internships.addStudent', ['internship' => $internship->id, 'id' => $user->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Add</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('internships.index') }}" class="btn btn-primary">Back to Internships</a>
    </div>
</body>
</html>
