@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">My Orders</h1>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.frontend.account-nav')</div>
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Order #</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td class="fw-semibold">{{ $order->order_number }}</td>
                                    <td>{{ $order->items()->count() }}</td>
                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                    <td><span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($order->payment_status) }}</span></td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td><a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
