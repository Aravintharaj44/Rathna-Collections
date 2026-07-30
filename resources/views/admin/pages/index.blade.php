@extends('layouts.admin')

@section('title', 'CMS Pages')
@section('page_title', 'CMS Pages')

@section('page_actions')
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Page</a>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Title</th><th>Slug</th><th>Status</th><th class="text-end">Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($pages as $page)
                            <tr>
                                <td class="fw-semibold">{{ $page->title }}</td>
                                <td><code>/page/{{ $page->slug }}</code></td>
                                <td><span class="badge {{ $page->status ? 'bg-success' : 'bg-secondary' }}">{{ $page->status ? 'Active' : 'Inactive' }}</span></td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this page?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No pages yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">{{ $pages->links() }}</div>
    </div>
@endsection
