<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Create holiday</title>

    <script>
        window.onload = function() {
            var dateStartInput = document.getElementById('date_start');
            var dateStopInput = document.getElementById('date_stop');

            dateStartInput.min = new Date().toISOString().split('T')[0];

            dateStartInput.addEventListener('change', function() {
                var selectedStartDate = new Date(dateStartInput.value);

                var selectedStartDay = selectedStartDate.getDay();

                if (selectedStartDay === 0 || selectedStartDay === 6 || isZiLibera(selectedStartDate)) {
                    dateStartInput.value = '';
                    dateStopInput.value = '';
                    alert('1. Selection of this date is not allowed.');
                    return;
                }

                dateStopInput.min = selectedStartDate.toISOString().split('T')[0];

                var selectedStopDate = new Date(dateStopInput.value);
                var selectedStopDay = selectedStopDate.getDay();

                if (selectedStopDay === 0 || selectedStopDay === 6 || isZiLibera(selectedStopDate)) {
                    dateStopInput.value = '';
                    alert('2. Selection of this date is not allowed.');
                }
            });

            dateStopInput.addEventListener('change', function() {
                var selectedStartDate = new Date(dateStartInput.value);
                var selectedStopDate = new Date(dateStopInput.value);
                var selectedStopDay = selectedStopDate.getDay();

                if (selectedStopDay === 0 || selectedStopDay === 6 || isZiLibera(selectedStopDate)) {
                    dateStopInput.value = '';
                    alert('3. Selection of this date is not allowed.');
                } else if (selectedStopDate < selectedStartDate) {
                    dateStopInput.value = dateStartInput.value;
                }
            });

            function isZiLibera(data) {
                var zileLibere = [
                    [30, 10],
                    [1, 11],
                    [25, 11],
                    [26, 11],
                ];

                var selectedStartDate = new Date(dateStartInput.value);
                var selectedStopDate = new Date(dateStopInput.value);

                var dayStart = selectedStartDate.getDate();
                var monthStart = selectedStartDate.getMonth();

                var dayStop = selectedStopDate.getDate();
                var monthStop = selectedStopDate.getMonth();

                var arrayDateStart = [dayStart, monthStart];
                var arrayDateStop = [dayStop, monthStop];

                for (var i = 0; i < zileLibere.length; i++) {
                    if (JSON.stringify(arrayDateStart) === JSON.stringify(zileLibere[i]) || JSON.stringify(arrayDateStop) === JSON.stringify(zileLibere[i])) {
                        return true;
                    }
                }

                return false;
            }
        };
    </script>
</head>
<body>
@include('components.header')
<h1>Create a Product</h1>
{{--<form method="post" action="{{ route('product.store') }}">--}}
<form method="post" action="/product">
    <div>
        @if($errors->any())
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
    @csrf
    @method('post')
    <div class="ml-5 mr-5">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="name">Name</label>
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
            <label class="col-sm-2 col-form-label" for="date_start">Date Start:</label>
            <div class="col-sm-10">
                <input type="date"
                       id="date_start"
                       name="date_start"
                       value="{{ old('date_start') }}"
                       required
                >
            </div>
            @error('date_start')
                <p>{{ $message }}</p>
            @enderror
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="date_stop">Date Stop:</label>
            <div class="col-sm-10">
                <input type="date"
                       id="date_stop"
                       name="date_stop"
                       value="{{ old('date_stop') }}"
                       required
                >
            </div>
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
                <button type="submit" class="btn btn-primary">Take</button>
            </div>
        </div>
    </div>
</form>
</body>
</html>
