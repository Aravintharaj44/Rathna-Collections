@extends('layouts.admin')

@section('title', 'Products')
@section('page_title', 'Products')

@section('page_actions')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Product</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search name or SKU…">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(request('category') == $cat->id)>{{ $cat->name }}</option>
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
                        <tr><th>Image</th><th>Name</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>
                                    @if ($product->thumbnail)
                                        <img src="{{ asset('storage/'.$product->thumbnail) }}" width="44" height="44" class="rounded object-fit-cover" alt="">
                                    @else <span class="text-muted"><i class="bi bi-image"></i></span> @endif
                                </td>
                                <td class="fw-semibold">{{ Str::limit($product->name, 40) }}</td>
                                <td><code>{{ $product->sku }}</code></td>
                                <td>{{ $product->category?->name }}</td>
                                <td>
                                    ₹{{ number_format($product->final_price, 2) }}
                                    @if ($product->offer_price)
                                        <br><small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $product->stock <= 5 ? 'bg-danger' : 'bg-light text-dark' }}">{{ $product->stock }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">{{ $product->status ? 'Active' : 'Inactive' }}</span>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-4">No products yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $products->links() }}</div>
    </div>
@endsection
