{{-- Reusable horizontal product grid.
     Expects: $heading (string), $products (Collection of Product) --}}
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h4 mb-0">{{ $heading }}</h2>
        <a href="{{ route('shop.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
    </div>

    @if ($products->isNotEmpty())
        <div class="row g-3">
            @foreach ($products as $product)
                <div class="col-6 col-md-4 col-lg-3">@include('frontend.partials.product-card')</div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Products will appear here once added from the admin panel.</p>
    @endif
</section>
