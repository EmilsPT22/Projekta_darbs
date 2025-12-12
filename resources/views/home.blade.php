@extends('layouts.app')

@section('content')
<div class="jumbotron">
    <h1 class="display-4">Welcome to praksesvietas.lv</h1>
    <p class="lead">Internship management and daily journal system.</p>

    @guest
        <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-3">Log in</a>
        <a href="{{ route('register') }}" class="btn btn-secondary btn-lg mt-3">Register</a>
    @endguest
</div>
@endsection
