@extends('layout')
@section('title', "Home Page")
@section('content')

    @auth
        <span>Welcome, {{ auth()->user()->name }}</span>
{{--        @include('holidays.index')--}}
{{--        @yield('content_holiday')--}}
    @else
        <span>Welcome, on my page</span>
    @endauth
@endsection
