@php($banner = $banner ?? null)
@include('partials.admin.errors')

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        @foreach (['slider' => 'Homepage Slider', 'offer' => 'Offer Banner', 'category' => 'Category Banner'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('type', $banner?->type) === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner?->sort_order ?? 0) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $banner?->title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $banner?->subtitle) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label">Link URL</label>
                    <input type="text" name="link" class="form-control" value="{{ old('link', $banner?->link) }}" placeholder="/shop?category=men">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $banner?->button_text) }}">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <label class="form-label">Image {{ $banner ? '' : '(required)' }}</label>
                @if ($banner?->image)
                    <img src="{{ asset('storage/'.$banner->image) }}" class="img-fluid rounded mb-2" alt="">
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" {{ $banner ? '' : 'required' }}>
                <small class="text-muted">Recommended 1600×500 for sliders.</small>

                <div class="form-check form-switch mt-3">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $banner?->status ?? true))>
                    <label class="form-check-label" for="status">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
