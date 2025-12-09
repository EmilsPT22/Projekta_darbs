@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Internship</h1>

    @if ($errors->any())
        <ul class="list-group mb-4 text-danger">
            @foreach ($errors->all() as $error)
                <li class="list-group-item">{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('internships.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Length (months)</label>
            <input type="number" name="length" class="form-control" value="{{ old('length') }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
        </div>

        <div class="form-group mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
        </div>

        <button class="btn btn-primary">Create</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
@endsection
