@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" class="row g-2">
                <div class="col-md-3"><input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Order number…"></div>
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All statuses</option>
                        @foreach ($statuses as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="payment_status" class="form-select form-select-sm">
                        <option value="">All payments</option>
                        @foreach (['pending','paid','failed','refunded'] as $p)
                            <option value="{{ $p }}" @selected(request('payment_status') === $p)>{{ ucfirst($p) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary">Filter</button></div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->user?->name ?? '—' }}</td>
                                <td>{{ $order->items_count ?? $order->items()->count() }}</td>
                                <td>₹{{ number_format($order->total, 2) }}</td>
                                <td><span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-secondary' }}">{{ ucfirst($order->payment_status) }}</span></td>
                                <td><span class="badge bg-info text-dark">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                                <td class="text-end"><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No orders yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $orders->links() }}</div>
    </div>
@endsection
