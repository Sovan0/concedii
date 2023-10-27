<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Edit</title>
</head>
<body>
<h1>Edit a Product</h1>
<div>
    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
</div>
<form method="post" action="{{ route('product.update', ['product' => $product]) }}">
    @csrf {{-- security reasons --}}
    @method('put')
    <div>
        <label>Name</label>
        <input type="text" name="name" placeholder="name" value="{{ $product->name }}">
    </div>
    <div>
        <label>Email</label>
        <input type="text" name="email" placeholder="email" value="{{ $product->email }}">
    </div>
    <div>
        <label>Date Start</label>
        <input type="date" name="date_start" placeholder="date_start" value="{{ $product->date_start }}">
    </div>
    <div>
        <label>Date Stop</label>
        <input type="date" name="date_stop" placeholder="date_stop" value="{{ $product->date_stop }}">
    </div>
    <div>
        <label>Description</label>
        <input type="text" name="description" placeholder="description" value="{{ $product->description }}">
    </div>
    <div>
        <input type="submit" value="Update" />
    </div>
</form>
</body>
</html>
