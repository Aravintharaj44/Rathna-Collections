@php($brand = $brand ?? null)
@include('partials.admin.errors')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $brand?->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-muted">(auto if blank)</span></label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand?->slug) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $brand?->description) }}</textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <label class="form-label">Logo</label>
                @if ($brand?->logo)
                    <img src="{{ asset('storage/'.$brand->logo) }}" class="img-fluid rounded mb-2" alt="">
                @endif
                <input type="file" name="logo" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $brand?->sort_order ?? 0) }}">
                </div>
                <div class="form-check form-switch">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $brand?->status ?? true))>
                    <label class="form-check-label" for="status">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
