<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Holidays</title>
</head>
@include('components.header')
<body>
<br>
<h1>The table with holidays</h1>
<br>
@if(auth()->user()->role === 'admin')
    <div>
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="ml-5 mr-5">
        <div class="row pb-3 d-flex align-items-center justify-content-center">
            <form method="GET" action="{{ route('filtered-products') }}" class="form-inline my-2 my-lg-0">
                <div class="row pt-3 pb-3 pr-4">
                    <label class="mt-2 mr-2"> Name: </label>
                    <input class="form-control mr-sm-2" type="search" id="search_name" placeholder="Search..." aria-label="Search" name="query" value="{{ $search_name ?? '' }}">
                </div>
                <div class="row pt-3 pb-3">
                    <div class="col-md-4 d-flex align-items-center">
                        <label class="mt-2 mr-2"> Start Date: </label>
                        <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date ?? '' }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <label class="mt-2 mr-2"> Stop Date: </label>
                        <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date ?? '' }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-3">Filter</button>
                        <button type="button" class="btn btn-danger" onclick="resetForm(event)">Reset</button>
                    </div>
                </div>
            </form>
        </div>
        <table class="table table-striped" id="table">
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
    </div>
@else
    <div>
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="ml-5 mr-5">
        <div class="row pb-3 d-flex align-items-center justify-content-center">
            <form method="GET" action="{{ route('filtered-products') }}">
                <div class="row pt-3 pb-3">
                    <div class="col-md-4 d-flex align-items-center">
                        <label class="mt-2 mr-2"> Start Date: </label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                               value="{{ $start_date ?? '' }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <label class="mt-2 mr-2"> Stop Date: </label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                               value="{{ $end_date ?? '' }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        <button type="submit" class="btn btn-primary mr-3">Filter</button>
                        <button type="button" class="btn btn-danger" onclick="resetForm(event)">Reset</button>
                    </div>
                </div>
            </form>
        </div>
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
            <a href="{{ route('product.show-create-product') }}" class="btn btn-primary">Take</a>
        </div>
    </div>
@endauth

<br/>
<div class="paginate">
    {{ $products->links() }}
</div>

</body>
</html>
<script>
    window.onload = function() {

        var urlParams = new URLSearchParams(window.location.search);
        var startDateParam = urlParams.get('start_date');
        var endDateParam = urlParams.get('end_date');
        var searchParam = urlParams.get('query');
        if (startDateParam) {
            document.getElementById('start_date').value = startDateParam;
        }
        if (endDateParam) {
            document.getElementById('end_date').value = endDateParam;
        }
        if (searchParam) {
            document.getElementById('search_name').value = searchParam;
        }
    };

    function resetForm(e) {
        e.preventDefault();

        var url = new URL(window.location.href);
        url.searchParams.delete('query');
        url.searchParams.delete('start_date');
        url.searchParams.delete('end_date');

        // var orderBy = url.searchParams.get('orderBy');
        // var orderDirection = url.searchParams.get('orderDirection');
        //
        // if (orderBy && orderDirection) {
        //     url.searchParams.set('orderBy', orderBy);
        //     url.searchParams.set('orderDirection', orderDirection);
        // }

        window.location.href = url.toString();
    }
</script>

<style>
    .paginate {
        display: flex;
        justify-content: center;
    }
    .w-5 {
        display: none;
    }
</style>
