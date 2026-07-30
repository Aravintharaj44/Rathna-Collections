@extends('layouts.admin')

@section('title', 'Customer')
@section('page_title', $customer->name)

@section('page_actions')
    <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Profile</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Email:</strong> {{ $customer->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $customer->phone ?? '—' }}</p>
                    <p class="mb-0"><strong>Joined:</strong> {{ $customer->created_at->format('d M Y') }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white fw-semibold">Addresses</div>
                <div class="card-body">
                    @forelse ($customer->addresses as $addr)
                        <div class="mb-2 pb-2 border-bottom small">
                            {{ $addr->name }} ({{ $addr->phone }})<br>
                            {{ $addr->line1 }}, {{ $addr->line2 }}<br>
                            {{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}
                        </div>
                    @empty
                        <p class="text-muted mb-0">No addresses.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Orders ({{ $customer->orders->count() }})</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light"><tr><th>Order #</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($customer->orders as $order)
                                    <tr>
                                        <td>{{ $order->order_number }}</td>
                                        <td>₹{{ number_format($order->total, 2) }}</td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span></td>
                                        <td><span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted py-4">No orders yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
