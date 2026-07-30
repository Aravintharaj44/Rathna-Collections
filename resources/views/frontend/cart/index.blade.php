@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">Shopping Cart</h1>

    @if ($items->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-bag fs-1 text-muted"></i>
            <p class="mt-2">Your cart is empty.</p>
            <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light"><tr><th>Product</th><th>Price</th><th style="width:130px">Qty</th><th>Subtotal</th><th></th></tr></thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($item->product?->thumbnail)
                                                    <img src="{{ asset('storage/'.$item->product->thumbnail) }}" width="50" height="50" class="rounded object-fit-cover" alt="">
                                                @endif
                                                <div>
                                                    <a href="{{ $item->product ? route('product.show', $item->product) : '#' }}" class="text-decoration-none text-dark fw-semibold">
                                                        {{ $item->product?->name ?? 'Product' }}
                                                    </a>
                                                    @if ($item->variant)<br><small class="text-muted">{{ $item->variant->label }}</small>@endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>₹{{ number_format($item->price, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex gap-1">
                                                @csrf @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="form-control form-control-sm">
                                                <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-repeat"></i></button>
                                            </form>
                                        </td>
                                        <td>₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                                        <td>
                                            <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary mt-3"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
            </div>

            {{-- Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Order Summary</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span>₹{{ number_format($summary['subtotal'], 2) }}</span></div>
                        @if ($summary['discount'] > 0)
                            <div class="d-flex justify-content-between mb-2 text-success"><span>Discount ({{ $summary['coupon']->code }})</span><span>- ₹{{ number_format($summary['discount'], 2) }}</span></div>
                        @endif
                        <div class="d-flex justify-content-between mb-2"><span>Tax</span><span>₹{{ number_format($summary['tax'], 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-2"><span>Shipping</span><span>₹{{ number_format($summary['shipping'], 2) }}</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold h5"><span>Total</span><span>₹{{ number_format($summary['total'], 2) }}</span></div>

                        {{-- Coupon --}}
                        <div class="mt-3">
                            @if ($summary['coupon'])
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf @method('DELETE')
                                    <div class="alert alert-success d-flex justify-content-between align-items-center py-2 mb-0">
                                        <span>{{ $summary['coupon']->code }} applied</span>
                                        <button class="btn btn-sm btn-outline-danger">Remove</button>
                                    </div>
                                </form>
                            @else
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    <input type="text" name="code" class="form-control form-control-sm" placeholder="Coupon code">
                                    <button class="btn btn-sm btn-outline-primary">Apply</button>
                                </form>
                            @endif
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mt-3">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
