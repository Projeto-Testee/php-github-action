@extends('layouts.app')
@section('content')
<div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center">
    <h1 class="text-white display-4" style="margin-top:80px">Simple Todo Application</h1>
    <h2 class="mt-40 h2-c">Easy way to organize your daily tasks</h2>
    <div class="mt-40">
        <a class="btn btn-light btn-lg" href="{{ route('login') }}" role="button" >Login</a>
        <a class="btn btn-secondary btn-lg" href="{{ route('register') }}" role="button">Register</a>
    </div>
</div>
@endsection
