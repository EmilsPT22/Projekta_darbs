@extends('layouts.app')

@section('content')
<div class="container">

    <h1 class="mb-4">Profile</h1>

    <div class="profile-card mb-4">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="profile-card mb-4">
        <h3>Password</h3>
        <a href="{{ route('profile.password') }}" class="profile-link">
            Change password
        </a>
    </div>

    <div class="profile-card">
        @include('profile.partials.delete-user-form')
    </div>

</div>
@endsection
