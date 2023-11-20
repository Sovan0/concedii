@extends('layout')
@section('title', 'Registration')
@section('content')
    <div class="container">
        <div class="mt-5">
            @if($errors->any())
                <div class="col-12">
                    @foreach($errors->all() as $error)
                        <div class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            @if(session()->has('error'))
                <div class="alert alert-danger">
                    {{ session('success') }}
                </div>
            @endif

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <form action="{{ route('registration.post') }}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
            <button type="submit" class="btn btn-primary">Registration</button>
        </form>
    </div>
@endsection
{{--<script>--}}
{{--    $('#password, #confirm_password').on('keyup', function () {--}}
{{--        if ($('#password').val() === $('#confirm_password').val()) {--}}
{{--            $('#message').html('Matching').css('color', 'green');--}}
{{--        } else--}}
{{--            $('#message').html('Not Matching').css('color', 'red');--}}
{{--    });--}}
{{--</script>--}}
