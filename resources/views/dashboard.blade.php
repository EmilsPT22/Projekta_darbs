@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Dashboard</h2>

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info">
                Welcome back, {{ auth()->user()->name }}!
            </div>
        </div>
    </div>

    <div class="row">
        @if(auth()->user()->hasRole('admin'))
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text">View and manage all users and their roles.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Manage Users</a>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasAnyRole(['admin', 'internship_manager']))
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Internships</h5>
                    <p class="card-text">Create and manage internships.</p>
                    <a href="{{ route('internships.index') }}" class="btn btn-primary">View Internships</a>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('student'))
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">My Internships</h5>
                    <p class="card-text">View your enrolled internships and journal.</p>
                    <a href="{{ route('internships.index') }}" class="btn btn-primary">View Internships</a>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->hasRole('teacher'))
        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">View Internships</h5>
                    <p class="card-text">Browse all internships and student journals.</p>
                    <a href="{{ route('internships.index') }}" class="btn btn-primary">View Internships</a>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-4 mb-4">
            <div class="card bg-dark border-secondary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text">Update your profile and password.</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
