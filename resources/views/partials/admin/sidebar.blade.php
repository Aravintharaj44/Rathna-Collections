{{-- Admin sidebar with active-state highlighting driven by route names. --}}
<aside class="admin-sidebar text-white d-flex flex-column flex-shrink-0 p-3">
    <a href="{{ route('admin.dashboard') }}" class="admin-brand text-white text-decoration-none mb-4 px-2 d-flex align-items-center gap-2">
        <img src="{{ asset('images/rc-mark.svg') }}" alt="Rathna Collections">
        <span>
            <span class="fs-6 fw-bold d-block text-gold">Rathna Collections</span>
            <span class="small text-white-50">Admin Panel</span>
        </span>
    </a>

    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-grid me-2"></i> Categories
            </a>
        </li>
        <li>
            <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i> Brands
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i> Products
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check me-2"></i> Orders
            </a>
        </li>
        <li>
            <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i> Customers
            </a>
        </li>
        <li>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated me-2"></i> Coupons
            </a>
        </li>
        <li>
            <a href="{{ route('admin.banners.index') }}" class="nav-link {{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <i class="bi bi-image me-2"></i> Banners
            </a>
        </li>
        <li>
            <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="bi bi-file-text me-2"></i> CMS Pages
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear me-2"></i> Settings
            </a>
        </li>
    </ul>

    <hr class="border-secondary">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </form>
</aside>
