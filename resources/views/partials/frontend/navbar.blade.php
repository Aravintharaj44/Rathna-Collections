<nav class="navbar navbar-expand-lg navbar-light rc-navbar sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/rc-logo.svg') }}" alt="Rathna Collections">
        </a>

        {{-- Always-visible actions: sits last on desktop, beside the burger on mobile --}}
        <div class="rc-nav-actions order-lg-last">
            @auth
                <a class="rc-icon-btn" href="{{ route('wishlist.index') }}" aria-label="Wishlist">
                    <i class="bi bi-heart"></i>
                    @if (($wishlistCount ?? 0) > 0)
                        <span class="rc-icon-badge bg-danger">{{ $wishlistCount }}</span>
                    @endif
                </a>
            @endauth

            <a class="rc-icon-btn" href="{{ route('cart.index') }}" aria-label="Cart">
                <i class="bi bi-bag"></i>
                @if (($cartCount ?? 0) > 0)
                    <span class="rc-icon-badge bg-primary">{{ $cartCount }}</span>
                @endif
            </a>

            @guest
                <a class="rc-icon-btn d-lg-none" href="{{ route('login') }}" aria-label="Login">
                    <i class="bi bi-person-circle"></i>
                </a>
                <a class="nav-link d-none d-lg-inline-block ms-1" href="{{ route('login') }}">Login</a>
                <a class="btn btn-sm btn-primary d-none d-lg-inline-block ms-2" href="{{ route('register') }}">Register</a>
            @else
                <div class="dropdown">
                    <button class="rc-icon-btn rc-user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        <span class="d-none d-lg-inline ms-1">{{ Str::limit(auth()->user()->name, 12) }}</span>
                        <i class="bi bi-caret-down-fill d-none d-lg-inline ms-1 small"></i>
                    </button>
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
                </div>
            @endguest

            <button class="navbar-toggler ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                    aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('shop.*') && !request('category') ? 'active' : '' }}" href="{{ route('shop.index') }}">Shop</a>
                </li>

                @foreach ($navCategories ?? [] as $cat)
                    @php
                        $kids   = $cat->children ?? collect();
                        $megaId = 'rc-mega-' . $cat->id;
                    @endphp

                    <li class="nav-item {{ $kids->isNotEmpty() ? 'position-static rc-has-mega' : '' }}">
                        <div class="rc-nav-row">
                            <a class="nav-link {{ request('category') === $cat->slug ? 'active' : '' }}"
                               href="{{ route('shop.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a>

                            @if ($kids->isNotEmpty())
                                <button class="rc-mega-toggle d-lg-none" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#{{ $megaId }}"
                                        aria-expanded="false" aria-controls="{{ $megaId }}"
                                        aria-label="Show {{ $cat->name }} sub-categories">
                                    <i class="bi bi-chevron-down"></i>
                                </button>
                            @endif
                        </div>

                        @if ($kids->isNotEmpty())
                            <div class="rc-mega collapse" id="{{ $megaId }}" data-bs-parent="#mainNav">
                                <div class="container">
                                    <div class="row g-lg-4">
                                        <div class="{{ $cat->image ? 'col-lg-9' : 'col-12' }}">
                                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                                                @foreach ($kids as $child)
                                                    <div class="col">
                                                        <a class="rc-mega-heading"
                                                           href="{{ route('shop.index', ['category' => $child->slug]) }}">
                                                            {{ $child->name }}
                                                            @if ($child->is_featured)
                                                                <span class="rc-mega-tag">New</span>
                                                            @endif
                                                        </a>

                                                        @if ($child->children->isNotEmpty())
                                                            @include('partials.frontend.mega-branch', [
                                                                'items' => $child->children,
                                                                'depth' => 1,
                                                            ])
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        @if ($cat->image)
                                            <div class="col-lg-3 d-none d-lg-block">
                                                <a class="rc-mega-promo" href="{{ route('shop.index', ['category' => $cat->slug]) }}">
                                                    <img src="{{ asset('storage/' . $cat->image) }}" alt="{{ $cat->name }}">
                                                    <span class="rc-mega-promo-body">
                                                        <span class="rc-mega-promo-title">{{ $cat->name }}</span>
                                                        <span class="rc-mega-promo-cta">Shop the collection <i class="bi bi-arrow-right"></i></span>
                                                    </span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="rc-mega-footer">
                                        <a href="{{ route('shop.index', ['category' => $cat->slug]) }}">
                                            View all {{ $cat->name }} <i class="bi bi-arrow-right-short"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>

            <form class="rc-nav-search" role="search" action="{{ route('shop.index') }}" method="GET">
                <i class="bi bi-search"></i>
                <input class="form-control form-control-sm" type="search" name="q" value="{{ request('q') }}"
                       placeholder="Search products…" aria-label="Search">
            </form>
        </div>
    </div>
</nav>