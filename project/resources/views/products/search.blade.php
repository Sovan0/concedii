<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

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
            <div>
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="ml-5 mr-5">
        <div class="row pb-3 d-flex align-items-center justify-content-center">
            <form class="form-inline my-2 my-lg-0" action="{{ route('products.search') }}" method="GET">
                <div class="row pt-3 pb-3 pr-4">
                    <input class="form-control mr-sm-2" type="search" id="searchName" placeholder="Search..." aria-label="Search" name="query" value="{{ session('search_query') ?? '' }}">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </div>
            </form>
            <form method="GET" action="{{ route('products.index') }}">
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
                        <button type="submit" class="btn btn-primary mr-3" onclick="filterProducts()">Filter</button>
                        <button type="button" class="btn btn-danger" onclick="resetForm()">Reset</button>
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
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>
            @foreach($searched_items as $product)
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
            <div>
                {{session('success')}}
            </div>
        @endif
    </div>
    <div class="ml-5 mr-5">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row pt-3 pb-3">
                <div class="col-md-4 d-flex align-items-center">
                    <label class="mr-2"> Start Date: </label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date ?? '' }}">
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <label class="mr-2"> Stop Date: </label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date ?? '' }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary" onclick="filterProducts()">Filter</button>
                    <button type="button" class="btn btn-danger" onclick="resetForm()">Reset</button>
                </div>
            </div>
        </form>
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
            @foreach($searched_items as $product)
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
    {!! $searched_items->appends(['query' => request('query')])->links() !!}
</div>

</body>
</html>

<script>
    window.onload = function() {
        var urlParams = new URLSearchParams(window.location.search);
        var startDateParam = urlParams.get('start_date');
        var endDateParam = urlParams.get('end_date');
        var searchParam = urlParams.get('searchName');

        if (startDateParam) {
            document.getElementById('start_date').value = startDateParam;
        }

        if (endDateParam) {
            document.getElementById('end_date').value = endDateParam;
        }

        if (searchParam) {
            document.getElementById('searchName').value = searchParam;
        }
    };

    function resetForm() {
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';

        document.querySelector('form').submit();
    }

    function filterProducts() {
        var startDate = document.getElementById('start_date').value;
        var endDate = document.getElementById('end_date').value;

        if (!startDate || !endDate) {
            alert('Please select both start and end dates.');
            return;
        }
        document.querySelector('form').submit();
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
