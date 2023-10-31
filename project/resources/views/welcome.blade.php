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
            <br>
            <div>
                <a href="{{ route('product.create') }}" class="btn btn-secondary">Create a leave request</a>
            </div>
            <span>Welcome, {{ auth()->user()->name }}</span>
            <br>
            <div>
                <a type="submit" class="btn btn-info" href="{{ route('product.create') }}">Take holiday</a>
            </div>
        @endif
    @else
        <span>Welcome, on my page</span>
    @endauth
@endsection
