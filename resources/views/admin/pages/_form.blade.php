@php($page = $page ?? null)
@include('partials.admin.errors')

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $page?->title) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Slug <span class="text-muted">(auto)</span></label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page?->slug) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Content <small class="text-muted">(HTML allowed)</small></label>
                <textarea name="content" rows="12" class="form-control font-monospace">{{ old('content', $page?->content) }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $page?->meta_title) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Meta Description</label>
                <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $page?->meta_description) }}">
            </div>
            <div class="col-12">
                <div class="form-check form-switch">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $page?->status ?? true))>
                    <label class="form-check-label" for="status">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
