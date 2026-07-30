{{-- Shared create/edit form. Expects $category (nullable) and $parents. --}}
@php($category = $category ?? null)
@include('partials.admin.errors')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $category?->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Slug <span class="text-muted">(auto if blank)</span></label>
                    <input type="text" name="slug" class="form-control" value="{{ old('slug', $category?->slug) }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Parent Category</label>
                    <select name="parent_id" class="form-select">
                        <option value="">— None (top level) —</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $category?->parent_id) == $parent->id)>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description', $category?->description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <label class="form-label">Image</label>
                @if ($category?->image)
                    <img src="{{ asset('storage/'.$category->image) }}" class="img-fluid rounded mb-2" alt="">
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">JPG/PNG/WEBP, max 2MB.</small>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $category?->sort_order ?? 0) }}">
                </div>
                <div class="form-check form-switch mb-2">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
                           @checked(old('is_featured', $category?->is_featured)) >
                    <label class="form-check-label" for="is_featured">Featured on homepage</label>
                </div>
                <div class="form-check form-switch">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status"
                           @checked(old('status', $category?->status ?? true)) >
                    <label class="form-check-label" for="status">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
