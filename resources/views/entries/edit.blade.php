@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Admin Comment – {{ $internship->name }}</h2>

    <form action="{{ route('entries.update', [$internship->id, $entry->id]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label>Supervisor Comment</label>
            <textarea name="supervisor_comment" class="form-control" rows="4">
{{ old('supervisor_comment', $entry->supervisor_comment) }}
</textarea>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('entries.index', $internship->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
