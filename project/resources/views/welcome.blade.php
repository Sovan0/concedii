@extends('layout')
@section('title', "Home Page")
@section('content')

    @auth
        <br>
        <span>Welcome, {{ auth()->user()->name }}</span>
        <br><br>
        <a type="submit" class="btn btn-info" href="/product">Holiday</a>
    @else
        <span>Welcome, on my page</span>
    @endauth
@endsection
