@extends('layouts.admin')

@section('title', 'Coupons')
@section('page_title', 'Coupons')

@section('page_actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Coupon</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Code</th><th>Type</th><th>Value</th><th>Min Purchase</th><th>Usage</th><th>Expires</th><th>Status</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td><code class="fw-bold">{{ $coupon->code }}</code></td>
                                <td>{{ ucfirst($coupon->type) }}</td>
                                <td>{{ $coupon->type === 'percent' ? $coupon->value.'%' : '₹'.number_format($coupon->value, 2) }}</td>
                                <td>₹{{ number_format($coupon->min_purchase, 2) }}</td>
                                <td>{{ $coupon->used_count }}{{ $coupon->usage_limit ? ' / '.$coupon->usage_limit : '' }}</td>
                                <td>{{ $coupon->expires_at?->format('d M Y') ?? '—' }}</td>
                                <td><span class="badge {{ $coupon->status ? 'bg-success' : 'bg-secondary' }}">{{ $coupon->status ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this coupon?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No coupons yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $coupons->links() }}</div>
    </div>
@endsection
