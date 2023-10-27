<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Create</title>
</head>
<body>
<h1>Create a Product</h1>
<div>
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>
<form method="post" action="{{ route('holiday.store') }}">
    @csrf {{-- security reasons --}}
    @method('post')
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="name" >Name</label>
        <div class="col-sm-10">
            <input class="form-control"
                   type="text"
                   name="name"
                   id="name"
                   value="{{ old('name') }}"
                   placeholder="Name"
                   required
            >
        </div>

        @error('name')
        <p>{{ $message }}</p>
        @enderror
    </div>

    <div class="form-group row">
        <label for="date_start">Date Start:</label>
        <input type="date"
               id="date_start"
               name="date_start"
               value="{{ old('date_start') }}"
               required
        >
        @error('date_start')
        <p>{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group row">
        <label for="date_stop">Date Stop:</label>
        <input type="date"
               id="date_stop"
               name="date_stop"
               value="{{ old('date_stop') }}"
               required
        >
        @error('date_stop')
        <p>{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group row">
        <label for="description" class="col-sm-2 col-form-label">Description</label>
        <div class="col-sm-10">
            <input class="form-control"
                   type="text"
                   name="description"
                   id="description"
                   placeholder="Description"
                   value="{{ old('description') }}"
                   required
            >

            @error('description')
            <p>{{ $message }}</p>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-10">
            <button type="submit" class="btn btn-primary">Send</button>
        </div>
    </div>
</form>
</body>
</html>
