<div class="card border-0 shadow-sm">
    <div class="list-group list-group-flush">
        <a href="{{ route('account.dashboard') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>
        <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.orders*') ? 'active' : '' }}">
            <i class="bi bi-bag me-2"></i> Orders
        </a>
        <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('wishlist.*') ? 'active' : '' }}">
            <i class="bi bi-heart me-2"></i> Wishlist
        </a>
        <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.addresses') ? 'active' : '' }}">
            <i class="bi bi-geo-alt me-2"></i> Addresses
        </a>
        <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action {{ request()->routeIs('account.profile') ? 'active' : '' }}">
            <i class="bi bi-person me-2"></i> Profile &amp; Password
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="list-group-item list-group-item-action text-danger w-100 text-start border-0">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
