@extends('layout')
@section('title', "Home Page")
@section('content')

    @auth
        @if(auth()->user()->role === 'admin')
            <br>
            <span>Admin {{ auth()->user()->name }}</span>
            <br><br>
            <a type="submit" class="btn btn-info" href="{{ route('product.index') }}">Show me holidays</a>
        @else
            <span>Welcome, {{ auth()->user()->name }}</span>
            <br />
            <br />
            <div>
                <a type="submit" class="btn btn-primary" href="{{ route('product.create') }}">Take</a>
            </div>
        @endif
    @else
        <span>Welcome, on my page</span>
    @endauth
@endsection
