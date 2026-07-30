@extends('layouts.app')

@section('title', 'Rathna Collections — Textile Fashion for Men, Women & Kids')

@section('content')
    {{-- Hero slider (falls back to a static banner until banners are added). --}}
    <section class="mb-5">
        <div class="container">
            @if ($sliders->isNotEmpty())
                <div id="heroSlider" class="carousel slide rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($sliders as $i => $slider)
                            <div class="carousel-item @if($i === 0) active @endif">
                                <img src="{{ asset('storage/'.$slider->image) }}" class="d-block w-100" alt="{{ $slider->title }}">
                            </div>
                        @endforeach
                    </div>
                    @if ($sliders->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    @endif
                </div>
            @else
                <div class="rc-hero rounded p-5 text-center shadow-sm">
                    <h1 class="display-5 fw-bold">New Season Arrivals</h1>
                    <p class="lead mb-4">Discover premium fabrics and modern styles for the whole family.</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-gold btn-lg">Shop Now</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Featured categories --}}
    <section class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">Shop by Category</h2>
        </div>
        @if ($featuredCategories->isNotEmpty())
            <div class="row g-3">
                @foreach ($featuredCategories as $category)
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="{{ route('shop.index', ['category' => $category->slug]) }}" class="text-decoration-none text-dark">
                            <div class="card border-0 text-center h-100 product-card">
                                <div class="ratio ratio-1x1 bg-light rounded">
                                    @if ($category->image)
                                        <img src="{{ asset('storage/'.$category->image) }}" class="rounded object-fit-cover" alt="{{ $category->name }}">
                                    @endif
                                </div>
                                <div class="card-body p-2"><small class="fw-semibold">{{ $category->name }}</small></div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted">Categories will appear here once added from the admin panel.</p>
        @endif
    </section>

    {{-- Reusable product row partials --}}
    @include('frontend.partials.product-row', ['heading' => 'Featured Products', 'products' => $featuredProducts])
    @include('frontend.partials.product-row', ['heading' => 'New Arrivals', 'products' => $newArrivals])
    @include('frontend.partials.product-row', ['heading' => 'Best Sellers', 'products' => $bestSellers])
@endsection
