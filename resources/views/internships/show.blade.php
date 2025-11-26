<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internship Details</title>
</head>
<body>
    <h1>Internship Details</h1>

    <ul>
        <li><strong>Name:</strong> {{ $internship->name }}</li>
        <li><strong>Description:</strong> {{ $internship->description }}</li>
        <li><strong>Length:</strong> {{ $internship->length }} months</li>
        <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }}</li>
        <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</li>
    </ul>


        <h2>Added Students</h2>
        @if($addedStudents->isEmpty())
            <p>No students added yet.</p>
        @else
            <ul>
                @foreach($addedStudents as $student)
                    <li>{{ $student->name }} ({{ $student->email }})</li>
                @endforeach
            </ul>
        @endif


        <h2>Add Students</h2>
        @if($users->isEmpty())
            <p>All students have been added.</p>
        @else
            <ul>
                @foreach($users as $user)
                    <li>
                        {{ $user->name }} ({{ $user->email }})
                        <form action="{{ route('internships.addStudent', ['internship' => $internship->id, 'id' => $user->id]) }}" 
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit">Add</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif


    <a href="{{ route('internships.index') }}">Back to Internships</a>
</body>
</html>
