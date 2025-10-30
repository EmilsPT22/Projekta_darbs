<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Internships</title>
</head>
<body>
    <h1>Internships</h1>

    <!-- Success Message -->
    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <a href="{{ route('internships.create') }}">Create Internship</a>

    <ul>
        @foreach($internships as $internship)
            <li>
                {{ $internship->name }} - {{ $internship->start_date }} to {{ $internship->end_date }}
                <a href="{{ route('internships.show', $internship) }}">View</a>
                <a href="{{ route('internships.edit', $internship) }}">Edit</a>
                <form action="{{ route('internships.destroy', $internship) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this internship?')">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
</body>
</html>
