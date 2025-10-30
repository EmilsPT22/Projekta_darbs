<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Internship</title>
</head>
<body>
    <h1>Create Internship</h1>
    
    @if ($errors->any())
        <ul style="color: red;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('internships.store') }}" method="POST">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}" required><br>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required>{{ old('description') }}</textarea><br>

        <label for="length">Length (months):</label>
        <input type="number" name="length" id="length" value="{{ old('length') }}" required><br>

        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required><br>

        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" required><br>

        <button type="submit">Create</button>
    </form>
</body>
</html>
