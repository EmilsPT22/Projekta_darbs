@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Change Password</h1>

    <div class="profile-card">
        @include('profile.partials.update-password-form')
    </div>

    <a href="{{ route('profile.edit') }}" class="profile-link mt-3 d-block">
        Back to profile
    </a>

</div>
@endsection
