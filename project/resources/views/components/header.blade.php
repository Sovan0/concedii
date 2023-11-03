<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">LeadSoft</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-text" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <form class="form-inline my-2 my-lg-0" action="{{ route('products.search') }}" method="GET">
                                <input class="form-control mr-sm-2" type="search" placeholder="Search..." aria-label="Search" name="query">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Admin's Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                        </li>
                    @endif
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
