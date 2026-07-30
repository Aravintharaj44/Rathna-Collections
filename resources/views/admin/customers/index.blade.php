@extends('layouts.admin')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" class="row g-2">
                <div class="col-md-4"><input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search name or email…"></div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary">Search</button></div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Email</th><th>Phone</th><th>Orders</th><th>Joined</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr>
                                <td class="fw-semibold">{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone ?? '—' }}</td>
                                <td>{{ $customer->orders_count }}</td>
                                <td>{{ $customer->created_at->format('d M Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i> View</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No customers yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $customers->links() }}</div>
    </div>
@endsection
