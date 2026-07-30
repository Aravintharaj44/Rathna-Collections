{{-- Reusable product card. Expects: $product --}}
<div class="card h-100 border-0 shadow-sm product-card">
    <a href="{{ route('product.show', $product) }}" class="text-decoration-none">
        <div class="ratio ratio-1x1 bg-light position-relative">
            @if ($product->thumbnail)
                <img src="{{ asset('storage/'.$product->thumbnail) }}" class="card-img-top object-fit-cover" alt="{{ $product->name }}">
            @else
                <div class="d-flex align-items-center justify-content-center text-muted"><i class="bi bi-image fs-1"></i></div>
            @endif
            @if ($product->discount_percent)
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $product->discount_percent }}%</span>
            @endif
            @if (! $product->in_stock)
                <span class="badge bg-dark position-absolute top-0 end-0 m-2">Out of stock</span>
            @endif
        </div>
    </a>
    <div class="card-body">
        <small class="text-muted">{{ $product->category?->name }}</small>
        <h3 class="h6 card-title text-truncate mb-1">
            <a href="{{ route('product.show', $product) }}" class="text-decoration-none text-dark">{{ $product->name }}</a>
        </h3>
        <div class="d-flex align-items-baseline gap-2 mb-2">
            <span class="fw-bold text-primary">₹{{ number_format($product->final_price, 2) }}</span>
            @if ($product->offer_price)
                <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
            @endif
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('cart.store', $product) }}" method="POST" class="flex-grow-1">
                @csrf
                <button class="btn btn-sm btn-primary w-100" {{ $product->in_stock ? '' : 'disabled' }}>
                    <i class="bi bi-bag-plus"></i> Add
                </button>
            </form>
            @auth
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-danger" title="Wishlist"><i class="bi bi-heart"></i></button>
                </form>
            @endauth
        </div>
    </div>
</div>
