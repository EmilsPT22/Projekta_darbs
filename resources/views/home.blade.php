@extends('layouts.app')

@section('content')
<div class="text-center mt-5">

    <h1 class="mb-3">praksesvietas.lv</h1>

    <p class="mb-4">
        Internship management and daily journal system
    </p>

    @guest
        <a href="{{ route('login') }}" class="btn btn-primary mr-2">
            Log in
        </a>

        <a href="{{ route('register') }}" class="btn btn-secondary">
            Register
        </a>
    @endguest

</div>
@endsection
