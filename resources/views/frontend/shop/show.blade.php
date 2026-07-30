@extends('layouts.app')

@section('title', ($product->meta_title ?: $product->name).' — Rathna Collections')
@section('meta_description', $product->meta_description ?: Str::limit(strip_tags($product->short_description), 150))

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Gallery --}}
        <div class="col-lg-6">
            <div class="border rounded overflow-hidden mb-2 bg-light">
                <img id="mainImage" src="{{ $product->thumbnail ? asset('storage/'.$product->thumbnail) : '' }}"
                     class="img-fluid w-100 object-fit-cover" style="aspect-ratio:1/1" alt="{{ $product->name }}">
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @if ($product->thumbnail)
                    <img src="{{ asset('storage/'.$product->thumbnail) }}" class="border rounded thumb" width="70" height="70"
                         style="cursor:pointer;object-fit:cover" onclick="document.getElementById('mainImage').src=this.src">
                @endif
                @foreach ($product->images as $img)
                    <img src="{{ asset('storage/'.$img->image) }}" class="border rounded thumb" width="70" height="70"
                         style="cursor:pointer;object-fit:cover" onclick="document.getElementById('mainImage').src=this.src">
                @endforeach
            </div>
        </div>

        {{-- Info --}}
        <div class="col-lg-6">
            <h1 class="h3">{{ $product->name }}</h1>
            <div class="text-muted mb-2">
                SKU: {{ $product->sku }} @if($product->brand) · {{ $product->brand->name }} @endif
            </div>

            @php($avg = $product->approvedReviews->avg('rating'))
            <div class="mb-3">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= round($avg) ? '-fill' : '' }} text-warning"></i>
                @endfor
                <small class="text-muted">({{ $product->approvedReviews->count() }} reviews)</small>
            </div>

            <div class="mb-3">
                <span class="h3 text-primary">₹{{ number_format($product->final_price, 2) }}</span>
                @if ($product->offer_price)
                    <span class="text-muted text-decoration-line-through ms-2">₹{{ number_format($product->price, 2) }}</span>
                    <span class="badge bg-danger ms-1">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            <p>{{ $product->short_description }}</p>

            <div class="mb-3">
                @if ($product->in_stock)
                    <span class="badge bg-success">In Stock</span>
                @else
                    <span class="badge bg-danger">Out of Stock</span>
                @endif
            </div>

            <form action="{{ route('cart.store', $product) }}" method="POST">
                @csrf
                @if ($product->variants->isNotEmpty())
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Choose option</label>
                        <select name="variant_id" class="form-select" style="max-width:300px">
                            @foreach ($product->variants as $v)
                                <option value="{{ $v->id }}" {{ $v->stock > 0 ? '' : 'disabled' }}>
                                    {{ $v->label }} @if($v->additional_price > 0) (+₹{{ number_format($v->additional_price, 2) }}) @endif
                                    {{ $v->stock > 0 ? '' : ' — out of stock' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="d-flex align-items-center gap-2 mb-3">
                    <label class="form-label mb-0">Qty</label>
                    <input type="number" name="quantity" value="1" min="1" max="99" class="form-control" style="width:90px">
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-lg" {{ $product->in_stock ? '' : 'disabled' }}>
                        <i class="bi bi-bag-plus"></i> Add to Cart
                    </button>
                    @auth
                        <button formaction="{{ route('wishlist.toggle', $product) }}" class="btn btn-outline-danger btn-lg">
                            <i class="bi bi-heart"></i>
                        </button>
                    @endauth
                </div>
            </form>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-5">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">Description</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#specs">Specifications</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">Reviews ({{ $product->approvedReviews->count() }})</button></li>
        </ul>
        <div class="tab-content border border-top-0 p-4 bg-white">
            <div class="tab-pane fade show active" id="desc">
                {!! nl2br(e($product->description)) ?: '<span class="text-muted">No description.</span>' !!}
            </div>
            <div class="tab-pane fade" id="specs">
                <table class="table table-sm w-auto">
                    <tr><th class="pe-4">Category</th><td>{{ $product->category?->name }}</td></tr>
                    <tr><th>Brand</th><td>{{ $product->brand?->name ?? '—' }}</td></tr>
                    <tr><th>Gender</th><td>{{ ucfirst($product->gender) }}</td></tr>
                    <tr><th>Fabric</th><td>{{ $product->fabric ?? '—' }}</td></tr>
                    <tr><th>Sleeve</th><td>{{ $product->sleeve_type ?? '—' }}</td></tr>
                </table>
            </div>
            <div class="tab-pane fade" id="reviews">
                @forelse ($product->approvedReviews as $review)
                    <div class="border-bottom pb-2 mb-2">
                        <div>
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning small"></i>
                            @endfor
                            <strong class="ms-2">{{ $review->title }}</strong>
                        </div>
                        <p class="mb-1">{{ $review->comment }}</p>
                        <small class="text-muted">— {{ $review->user->name }}, {{ $review->created_at->format('d M Y') }}</small>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet.</p>
                @endforelse

                @auth
                    <h6 class="mt-4">Write a review</h6>
                    <form action="{{ route('review.store', $product) }}" method="POST" class="col-lg-6">
                        @csrf
                        <div class="mb-2">
                            <select name="rating" class="form-select form-select-sm" required>
                                <option value="">Rating…</option>
                                @for ($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>@endfor
                            </select>
                        </div>
                        <div class="mb-2"><input type="text" name="title" class="form-control form-control-sm" placeholder="Title"></div>
                        <div class="mb-2"><textarea name="comment" rows="3" class="form-control form-control-sm" placeholder="Your review"></textarea></div>
                        <button class="btn btn-sm btn-primary">Submit Review</button>
                    </form>
                @else
                    <p class="mt-3"><a href="{{ route('login') }}">Login</a> to write a review.</p>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <div class="mt-5">
            <h2 class="h4 mb-3">Related Products</h2>
            <div class="row g-3">
                @foreach ($related as $product)
                    <div class="col-6 col-md-3">@include('frontend.partials.product-card')</div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
