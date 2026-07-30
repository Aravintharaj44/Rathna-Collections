@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">My Wishlist</h1>

    @if ($wishlists->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-heart fs-1 text-muted"></i>
            <p class="mt-2">Your wishlist is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Browse Products</a>
        </div>
    @else
        <div class="row g-3">
            @foreach ($wishlists as $wishlist)
                @continue(! $wishlist->product)
                <div class="col-6 col-md-3">
                    @php($product = $wishlist->product)
                    <div class="position-relative">
                        @include('frontend.partials.product-card')
                        <form action="{{ route('wishlist.destroy', $wishlist) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Remove"><i class="bi bi-x"></i></button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
