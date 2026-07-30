@extends('layouts.app')

@section('title', 'Shop — Rathna Collections')

@section('content')
<div class="container">
    <div class="row">
        {{-- Filters sidebar --}}
        <div class="col-lg-3 mb-4">
            <form method="GET" action="{{ route('shop.index') }}">
                {{-- preserve search term --}}
                @if (request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold">Category</div>
                    <div class="card-body">
                        <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold">Brand</div>
                    <div class="card-body">
                        <select name="brand" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">All</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->slug }}" @selected(request('brand') === $brand->slug)>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold">Gender</div>
                    <div class="card-body">
                        @foreach (['men','women','kids','unisex'] as $g)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" value="{{ $g }}"
                                       id="g{{ $g }}" @checked(request('gender') === $g) onchange="this.form.submit()">
                                <label class="form-check-label" for="g{{ $g }}">{{ ucfirst($g) }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white fw-semibold">Price</div>
                    <div class="card-body">
                        <div class="d-flex gap-2 mb-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control form-control-sm" placeholder="Min">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control form-control-sm" placeholder="Max">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="availability" value="in_stock"
                                   id="inStock" @checked(request('availability') === 'in_stock')>
                            <label class="form-check-label" for="inStock">In stock only</label>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary w-100 btn-sm">Apply Filters</button>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary w-100 btn-sm mt-2">Clear</a>
            </form>
        </div>

        {{-- Products --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">{{ $products->total() }} products</span>
                <form method="GET">
                    @foreach (request()->except('sort', 'page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="sort" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                        <option value="">Newest</option>
                        <option value="price_low" @selected(request('sort')==='price_low')>Price: Low to High</option>
                        <option value="price_high" @selected(request('sort')==='price_high')>Price: High to Low</option>
                        <option value="popular" @selected(request('sort')==='popular')>Popular</option>
                        <option value="rating" @selected(request('sort')==='rating')>Rating</option>
                    </select>
                </form>
            </div>

            @if ($products->isNotEmpty())
                <div class="row g-3">
                    @foreach ($products as $product)
                        <div class="col-6 col-md-4">@include('frontend.partials.product-card')</div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $products->links() }}</div>
            @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-search fs-1"></i>
                    <p class="mt-2">No products match your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
