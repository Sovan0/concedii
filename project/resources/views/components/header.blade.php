<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">LeadSoft</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-text" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <div>
                        <form method="GET" action="/product">
                            <button type="submit" class="btn btn-info">Holiday</button>
                        </form>
                    </div>
{{--                    <form method="GET" action="/holiday">--}}
{{--                        <a class="nav-link" href="{{ route('holidays.index') }}">Holiday</a>--}}
{{--                    </form>--}}
{{--                    <form method="GET" action="/holiday">--}}
{{--                        <button type="submit" class="btn btn-info">Holiday</button>--}}
{{--                    </form>--}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('registration') }}">Registration</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
