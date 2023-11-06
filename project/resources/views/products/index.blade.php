<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Holidays</title>
</head>
@include('components.header')
<body>
<br>
<h1>Holiday</h1>
<br>
@if(auth()->user()->role === 'admin')
    <div>
        @if(session()->has('success'))
            <div>
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="ml-5 mr-5">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Date Start</th>
                <th scope="col">Date Stop</th>
                <th scope="col">Description</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{  date('Y-m-d',strtotime($product->date_start)) }}</td>
                    <td>{{  date('Y-m-d',strtotime($product->date_stop)) }}</td>
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
{{--        <br>--}}
{{--        <div>--}}
{{--            <a href="{{ route('product.create') }}" class="btn btn-primary">Take</a>--}}
{{--        </div>--}}
    </div>
@else
    <div>
        @if(session()->has('success'))
            <div>
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="ml-5 mr-5">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Date Start</th>
                <th scope="col">Date Stop</th>
                <th scope="col">Description</th>
                <th scope="col">Edit</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{  date('Y-m-d',strtotime($product->date_start)) }}</td>
                    <td>{{  date('Y-m-d',strtotime($product->date_stop)) }}</td>
                    <td>{{ $product->description }}</td>
                    <td>
                        <a href="{{ route('product.edit', ['product' => $product]) }}" class="btn btn-primary">Edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br>
        <div>
            <a href="{{ route('product.create') }}" class="btn btn-primary">Take</a>
        </div>
    </div>
@endauth

<br/>
<div class="paginate">
    {!! $products->links() !!}
</div>

</body>
</html>

<style>
    .paginate {
        display: flex;
        justify-content: center;
    }
    .w-5 {
        display: none;
    }
</style>
