@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    {{-- KPI cards --}}
    <div class="row g-3 mb-4">
        @php
            $cards = [
                ['label' => 'Total Orders',    'value' => number_format($totalOrders),          'icon' => 'cart-check',        'bg' => 'primary'],
                ['label' => 'Total Revenue',   'value' => '₹'.number_format($totalRevenue, 2),  'icon' => 'currency-rupee',    'bg' => 'success'],
                ['label' => 'Customers',       'value' => number_format($totalCustomers),       'icon' => 'people',            'bg' => 'info'],
                ['label' => 'Products',        'value' => number_format($totalProducts),        'icon' => 'box-seam',          'bg' => 'secondary'],
                ['label' => 'Pending Orders',  'value' => number_format($pendingOrders),        'icon' => 'hourglass-split',   'bg' => 'warning'],
                ['label' => 'Low Stock',       'value' => number_format($lowStockProducts),     'icon' => 'exclamation-triangle', 'bg' => 'danger'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-6 col-lg-4 col-xl-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-{{ $card['bg'] }} bg-opacity-10 text-{{ $card['bg'] }}"
                              style="width:48px;height:48px;">
                            <i class="bi bi-{{ $card['icon'] }} fs-4"></i>
                        </span>
                        <div>
                            <div class="h5 mb-0">{{ $card['value'] }}</div>
                            <small class="text-muted">{{ $card['label'] }}</small>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Recent orders --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Recent Orders</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="fw-semibold">{{ $order->order_number }}</td>
                                <td>{{ $order->user?->name ?? '—' }}</td>
                                <td>₹{{ number_format($order->total, 2) }}</td>
                                <td><span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span></td>
                                <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No orders yet. Orders will appear here after Phase 4 (checkout &amp; payments).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
