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

    <a href="{{ route('internships.index') }}">Back to Internships</a>
</body>
</html>
