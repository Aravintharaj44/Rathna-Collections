@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center py-4">
            <i class="bi bi-check-circle-fill text-success" style="font-size:4rem"></i>
            <h1 class="h3 mt-3">Thank you for your order!</h1>
            <p class="text-muted">Your order <strong>{{ $order->order_number }}</strong> has been placed.</p>

            <div class="card border-0 shadow-sm text-start mt-4">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between">
                    <span>Order {{ $order->order_number }}</span>
                    <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body">
                    @foreach ($order->items as $item)
                        <div class="d-flex justify-content-between small mb-2">
                            <span>{{ $item->product_name }} @if($item->variant_label)({{ $item->variant_label }})@endif × {{ $item->quantity }}</span>
                            <span>₹{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between fw-bold"><span>Total Paid</span><span>₹{{ number_format($order->total, 2) }}</span></div>
                    <div class="small text-muted mt-1">Payment: {{ ucfirst($order->payment_status) }}</div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-primary">View Order</a>
                <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
@endsection
