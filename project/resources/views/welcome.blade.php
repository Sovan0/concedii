@extends('layout')
@section('title', "Home Page")
@section('content')
    @auth
        @if(auth()->user()->role === 'admin')
            <br>
            <span>Welcome, {{ auth()->user()->name }}</span>
            <br><br>
            <a type="submit" class="btn btn-primary" href="{{ route('products.index') }}">Show me</a>

            <h1 style="text-align: center; color: #252525;">Bar Chart</h1>

            <div style="width: 1200px; margin: auto;">
                <canvas id="myChart"></canvas>
            </div>


            <script>
                const DATA_COUNT = 7;
                const NUMBER_CFG = {count: DATA_COUNT, min: 0, max: 100};

                fetch("{{ route('bar-chart') }}")
                    .then(response => response.json())
                    .then(data => {
                        const dynamicData = {
                            datasets: [{
                                label: 'Day',
                                backgroundColor: 'rgb(255, 99, 132)',
                                data: data.daysInYear,
                            }]
                        };

                        const dynamicConfig = {
                            type: 'bar',
                            data: dynamicData,
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: {
                                        position: 'top',
                                    },
                                    title: {
                                        display: true,
                                        text: 'Bar Chart with holidays'
                                    }
                                }
                            },
                        };

                        new Chart(
                            document.getElementById('myChart'),
                            dynamicConfig
                        );
                    })
                    .catch(error => console.error('Error fetching bar chart data:', error));

                const config = {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Bar Chart with holidays'
                            }
                        }
                    },
                };

                new Chart(
                    document.getElementById('myChart'),
                    config
                );

                console.log(data);
            </script>
        @else
            <span>Welcome, {{ auth()->user()->name }}</span>
            <br />
            <br />
            <div>
                <a class="btn btn-primary" href="{{ route('products.index') }}">Show me</a>
                <a class="btn btn-primary" href="{{ route('product.show-create-product') }}">Takes</a>
            </div>
        @endif
    @else
        <span>Welcome, on my page</span>
    @endauth
@endsection
