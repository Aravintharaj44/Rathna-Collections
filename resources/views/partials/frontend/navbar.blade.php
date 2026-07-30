<nav class="navbar navbar-expand-lg navbar-light rc-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/rc-logo.svg') }}" alt="Rathna Collections">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('shop.*') && !request('category') ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a></li>
                @foreach ($navCategories ?? [] as $cat)
                    <li class="nav-item">
                        <a class="nav-link {{ request('category') === $cat->slug ? 'active' : '' }}"
                           href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>
                    </li>
                @endforeach
            </ul>

            {{-- Search --}}
            <form class="d-flex me-3" role="search" action="{{ route('shop.index') }}" method="GET">
                <input class="form-control form-control-sm" type="search" name="q" value="{{ request('q') }}"
                       placeholder="Search products…" aria-label="Search">
            </form>

            <ul class="navbar-nav align-items-lg-center">
                @auth
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('wishlist.index') }}" title="Wishlist">
                            <i class="bi bi-heart fs-5"></i>
                            @if (($wishlistCount ?? 0) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $wishlistCount }}</span>
                            @endif
                        </a>
                    </li>
                @endauth
                <li class="nav-item">
                    <a class="nav-link position-relative" href="{{ route('cart.index') }}" title="Cart">
                        <i class="bi bi-bag fs-5"></i>
                        @if (($cartCount ?? 0) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{ $cartCount }}</span>
                        @endif
                    </a>
                </li>

                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-primary ms-lg-2" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ Str::limit(auth()->user()->name, 12) }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if (auth()->user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li><a class="dropdown-item" href="{{ route('account.dashboard') }}">My Account</a></li>
                            <li><a class="dropdown-item" href="{{ route('account.orders') }}">My Orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
