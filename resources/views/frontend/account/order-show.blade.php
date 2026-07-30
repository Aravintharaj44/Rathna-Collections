@extends('layouts.app')

@section('title', 'Order '.$order->order_number)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Order {{ $order->order_number }}</h1>
        <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between">
                    <span class="fw-semibold">Items</span>
                    <span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Product</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }} @if($item->variant_label)<br><small class="text-muted">{{ $item->variant_label }}</small>@endif</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="2" class="text-end">Subtotal</td><td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td></tr>
                            <tr><td colspan="2" class="text-end">Discount</td><td class="text-end">- ₹{{ number_format($order->discount, 2) }}</td></tr>
                            <tr><td colspan="2" class="text-end">Tax</td><td class="text-end">₹{{ number_format($order->tax, 2) }}</td></tr>
                            <tr><td colspan="2" class="text-end">Shipping</td><td class="text-end">₹{{ number_format($order->shipping, 2) }}</td></tr>
                            <tr class="fw-bold"><td colspan="2" class="text-end">Total</td><td class="text-end">₹{{ number_format($order->total, 2) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Shipping Address</div>
                <div class="card-body small">
                    @php($s = $order->shipping_address)
                    @if ($s)
                        {{ $s['name'] ?? '' }} ({{ $s['phone'] ?? '' }})<br>
                        {{ $s['line1'] ?? '' }} {{ $s['line2'] ?? '' }}<br>
                        {{ $s['city'] ?? '' }}, {{ $s['state'] ?? '' }} - {{ $s['pincode'] ?? '' }}
                    @endif
                </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Payment</div>
                <div class="card-body small">
                    <p class="mb-1"><strong>Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                    <p class="mb-0"><strong>Placed:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
