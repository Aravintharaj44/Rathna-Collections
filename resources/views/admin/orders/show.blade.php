@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)
@section('page_title', 'Order '.$order->order_number)

@section('page_actions')
    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer"></i> Invoice</a>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Items</div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Product</th><th>Variant</th><th>Price</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}<br><small class="text-muted">{{ $item->sku }}</small></td>
                                    <td>{{ $item->variant_label ?? '—' }}</td>
                                    <td>₹{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr><td colspan="4" class="text-end">Subtotal</td><td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td></tr>
                            <tr><td colspan="4" class="text-end">Discount</td><td class="text-end">- ₹{{ number_format($order->discount, 2) }}</td></tr>
                            <tr><td colspan="4" class="text-end">Tax</td><td class="text-end">₹{{ number_format($order->tax, 2) }}</td></tr>
                            <tr><td colspan="4" class="text-end">Shipping</td><td class="text-end">₹{{ number_format($order->shipping, 2) }}</td></tr>
                            <tr class="fw-bold"><td colspan="4" class="text-end">Grand Total</td><td class="text-end">₹{{ number_format($order->total, 2) }}</td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white fw-semibold">Shipping Address</div>
                        <div class="card-body small">
                            @php($ship = $order->shipping_address)
                            @if ($ship)
                                {{ $ship['name'] ?? '' }} ({{ $ship['phone'] ?? '' }})<br>
                                {{ $ship['line1'] ?? '' }} {{ $ship['line2'] ?? '' }}<br>
                                {{ $ship['city'] ?? '' }}, {{ $ship['state'] ?? '' }} - {{ $ship['pincode'] ?? '' }}
                            @else <span class="text-muted">—</span> @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white fw-semibold">Billing Address</div>
                        <div class="card-body small">
                            @php($bill = $order->billing_address)
                            @if ($bill)
                                {{ $bill['name'] ?? '' }} ({{ $bill['phone'] ?? '' }})<br>
                                {{ $bill['line1'] ?? '' }} {{ $bill['line2'] ?? '' }}<br>
                                {{ $bill['city'] ?? '' }}, {{ $bill['state'] ?? '' }} - {{ $bill['pincode'] ?? '' }}
                            @else <span class="text-muted">—</span> @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Customer</div>
                <div class="card-body">
                    <p class="mb-1">{{ $order->user?->name }}</p>
                    <p class="mb-1 small text-muted">{{ $order->user?->email }}</p>
                    <p class="mb-0 small text-muted">Placed {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Update Status</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select">
                                @foreach ($statuses as $s)
                                    <option value="{{ $s }}" @selected($order->status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select">
                                @foreach (['pending','paid','failed','refunded'] as $p)
                                    <option value="{{ $p }}" @selected($order->payment_status === $p)>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">Update</button>
                    </form>
                </div>
            </div>

            @if ($order->payment)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Payment</div>
                    <div class="card-body small">
                        <p class="mb-1"><strong>Gateway:</strong> {{ ucfirst($order->payment->gateway) }}</p>
                        <p class="mb-1"><strong>Payment ID:</strong> {{ $order->payment->razorpay_payment_id ?? '—' }}</p>
                        <p class="mb-0"><strong>Status:</strong> {{ ucfirst($order->payment->status) }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
