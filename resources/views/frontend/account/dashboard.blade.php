@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">My Account</h1>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.frontend.account-nav')</div>
        <div class="col-lg-9">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center"><div class="card-body">
                        <div class="h3 text-primary">{{ $ordersCount }}</div><small class="text-muted">Orders</small>
                    </div></div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center"><div class="card-body">
                        <div class="h3 text-danger">{{ $wishlistCount }}</div><small class="text-muted">Wishlist</small>
                    </div></div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm text-center"><div class="card-body">
                        <div class="h3 text-success">{{ $addressCount }}</div><small class="text-muted">Addresses</small>
                    </div></div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Recent Orders</div>
                <div class="card-body p-0">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Order #</th><th>Total</th><th>Status</th><th>Date</th><th></th></tr></thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>₹{{ number_format($order->total, 2) }}</td>
                                    <td><span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td><a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No orders yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
