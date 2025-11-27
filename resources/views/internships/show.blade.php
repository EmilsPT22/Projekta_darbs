@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Internship Details</h1>

    <ul class="list-group mb-4">
        <li class="list-group-item"><strong>Name:</strong> {{ $internship->name }}</li>
        <li class="list-group-item"><strong>Description:</strong> {{ $internship->description }}</li>
        <li class="list-group-item"><strong>Length:</strong> {{ $internship->length }} months</li>
        <li class="list-group-item"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($internship->start_date)->format('d/m/Y') }}</li>
        <li class="list-group-item"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($internship->end_date)->format('d/m/Y') }}</li>
    </ul>

    <a href="{{ route('internships.index') }}" class="btn btn-primary">Back to Internships</a>
</div>
@endsection
