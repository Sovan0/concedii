<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Laravel</title>
</head>
<body>
<h1>Holiday</h1>
<br>
<div>
    @if(session()->has('success'))
        <div>
            {{session('success')}}
        </div>
    @endif
</div>
<div>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Date Start</th>
            <th scope="col">Date Stop</th>
            <th scope="col">Description</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product )
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ $product->email }}</td>
                <td>{{ $product->date_start }}</td>
                <td>{{ $product->date_stop }}</td>
                <td>{{ $product->description }}</td>
                <td>
                    <a href="{{ route('product.edit', ['product' => $product]) }}" class="btn btn-primary">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{ route('product.delete', ['product' => $product]) }}">
                        @csrf
                        @method('delete')
                        <input type="submit" class="btn btn-danger" value="Delete"/>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <br>
    <div>
        <a href="{{ route('product.create') }}" class="btn btn-secondary">Create a leave request</a>
    </div>
</div>
</body>
</html>