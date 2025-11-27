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
            <label for="name">Name</label>
            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                class="form-control"
                required
            >
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea
                name="description"
                id="description"
                class="form-control"
                required
            >{{ old('description') }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label for="length">Length (months)</label>
            <input
                type="number"
                name="length"
                id="length"
                value="{{ old('length') }}"
                class="form-control"
                required
            >
        </div>

        <div class="form-group mb-3">
            <label for="start_date">Start Date</label>
            <input
                type="date"
                name="start_date"
                id="start_date"
                value="{{ old('start_date') }}"
                class="form-control"
                required
            >
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input
                type="date"
                name="end_date"
                id="end_date"
                value="{{ old('end_date') }}"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
@endsection
