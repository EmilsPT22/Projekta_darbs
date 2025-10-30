<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Internship</title>
</head>
<body>
    <h1>Edit Internship</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('internships.update', $internship) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name', $internship->name) }}" required><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required>{{ old('description', $internship->description) }}</textarea><br>

        <label for="length">Length (months):</label>
        <input type="number" name="length" id="length" value="{{ old('length', $internship->length) }}" required><br>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $internship->start_date) }}" required><br>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $internship->end_date) }}" required><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
