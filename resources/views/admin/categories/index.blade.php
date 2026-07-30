@extends('layouts.admin')

@section('title', 'Categories')
@section('page_title', 'Categories')

@section('page_actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Category
    </a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <form method="GET" class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm"
                           placeholder="Search categories…">
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-outline-secondary">Search</button>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    @if ($category->image)
                                        <img src="{{ asset('storage/'.$category->image) }}" width="44" height="44"
                                             class="rounded object-fit-cover" alt="">
                                    @else
                                        <span class="text-muted"><i class="bi bi-image"></i></span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td>{{ $category->parent?->name ?? '—' }}</td>
                                <td>
                                    @if ($category->is_featured)
                                        <span class="badge bg-success-subtle text-success">Yes</span>
                                    @else <span class="text-muted">No</span> @endif
                                </td>
                                <td>
                                    <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $category->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this category?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $categories->links() }}</div>
    </div>
@endsection
