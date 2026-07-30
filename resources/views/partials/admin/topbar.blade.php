<nav class="navbar navbar-expand bg-white border-bottom px-4 py-2">
    <span class="navbar-text small text-muted">
        <i class="bi bi-calendar3 me-1"></i> {{ now()->format('l, d M Y') }}
    </span>

    <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item me-3">
            <a class="nav-link" href="{{ route('home') }}" target="_blank" title="View storefront">
                <i class="bi bi-shop"></i> View Site
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</nav>
