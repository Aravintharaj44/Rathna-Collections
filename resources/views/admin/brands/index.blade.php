@extends('layouts.admin')

@section('title', 'Brands')
@section('page_title', 'Brands')

@section('page_actions')
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Brand</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search brands…">
                </div>
                <div class="col-auto"><button class="btn btn-sm btn-outline-secondary">Search</button></div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Logo</th><th>Name</th><th>Products</th><th>Status</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($brands as $brand)
                            <tr>
                                <td>
                                    @if ($brand->logo)
                                        <img src="{{ asset('storage/'.$brand->logo) }}" width="44" height="44" class="rounded object-fit-cover" alt="">
                                    @else <span class="text-muted"><i class="bi bi-image"></i></span> @endif
                                </td>
                                <td class="fw-semibold">{{ $brand->name }}</td>
                                <td>{{ $brand->products()->count() }}</td>
                                <td>
                                    <span class="badge {{ $brand->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $brand->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this brand?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No brands yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $brands->links() }}</div>
    </div>
@endsection
