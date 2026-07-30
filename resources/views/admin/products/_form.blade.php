@php($product = $product ?? null)
@include('partials.admin.errors')

<div class="row g-4">
    {{-- Main column --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Basic Information</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product?->name) }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Slug <span class="text-muted">(auto)</span></label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug', $product?->slug) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">SKU <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product?->sku) }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Short Description</label>
                    <textarea name="short_description" rows="2" class="form-control" maxlength="500">{{ old('short_description', $product?->short_description) }}</textarea>
                </div>
                <div class="mb-0">
                    <label class="form-label">Full Description</label>
                    <textarea name="description" rows="6" class="form-control">{{ old('description', $product?->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Pricing &amp; Stock</div>
            <div class="card-body row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product?->price) }}" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Offer Price (₹)</label>
                    <input type="number" step="0.01" name="offer_price" class="form-control" value="{{ old('offer_price', $product?->offer_price) }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tax (%)</label>
                    <input type="number" step="0.01" name="tax" class="form-control" value="{{ old('tax', $product?->tax ?? 0) }}">
                </div>
                <div class="col-md-4 mb-0">
                    <label class="form-label">Base Stock <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control" value="{{ old('stock', $product?->stock ?? 0) }}" required>
                </div>
            </div>
        </div>

        {{-- Variants --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Variants <small class="text-muted">(color / size / stock)</small></span>
                <button type="button" class="btn btn-sm btn-outline-primary" id="addVariant"><i class="bi bi-plus"></i> Add row</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Color</th><th>Size</th><th>Extra Price (₹)</th><th>Stock</th><th></th></tr>
                    </thead>
                    <tbody id="variantRows">
                        @php($variants = old('variant_color') ? array_keys(old('variant_color')) : ($product?->variants ?? collect()))
                        @if ($product && $product->variants->isNotEmpty() && ! old('variant_color'))
                            @foreach ($product->variants as $v)
                                <tr>
                                    <td><input type="text" name="variant_color[]" class="form-control form-control-sm" value="{{ $v->color }}"></td>
                                    <td><input type="text" name="variant_size[]" class="form-control form-control-sm" value="{{ $v->size }}"></td>
                                    <td><input type="number" step="0.01" name="variant_price[]" class="form-control form-control-sm" value="{{ $v->additional_price }}"></td>
                                    <td><input type="number" name="variant_stock[]" class="form-control form-control-sm" value="{{ $v->stock }}"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger removeVariant"><i class="bi bi-x"></i></button></td>
                                </tr>
                            @endforeach
                        @elseif (old('variant_color'))
                            @foreach (old('variant_color') as $i => $color)
                                <tr>
                                    <td><input type="text" name="variant_color[]" class="form-control form-control-sm" value="{{ $color }}"></td>
                                    <td><input type="text" name="variant_size[]" class="form-control form-control-sm" value="{{ old('variant_size')[$i] ?? '' }}"></td>
                                    <td><input type="number" step="0.01" name="variant_price[]" class="form-control form-control-sm" value="{{ old('variant_price')[$i] ?? 0 }}"></td>
                                    <td><input type="number" name="variant_stock[]" class="form-control form-control-sm" value="{{ old('variant_stock')[$i] ?? 0 }}"></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-danger removeVariant"><i class="bi bi-x"></i></button></td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar column --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Organization</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Category <span class="text-danger">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">— Select —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $product?->category_id) == $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Brand</label>
                    <select name="brand_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        @foreach (['men','women','kids','unisex'] as $g)
                            <option value="{{ $g }}" @selected(old('gender', $product?->gender ?? 'unisex') == $g)>{{ ucfirst($g) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fabric</label>
                    <input type="text" name="fabric" class="form-control" value="{{ old('fabric', $product?->fabric) }}">
                </div>
                <div class="mb-0">
                    <label class="form-label">Sleeve Type</label>
                    <input type="text" name="sleeve_type" class="form-control" value="{{ old('sleeve_type', $product?->sleeve_type) }}">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Images</div>
            <div class="card-body">
                <label class="form-label">Thumbnail</label>
                @if ($product?->thumbnail)
                    <img src="{{ asset('storage/'.$product->thumbnail) }}" class="img-fluid rounded mb-2" alt="">
                @endif
                <input type="file" name="thumbnail" class="form-control mb-3" accept="image/*">

                <label class="form-label">Gallery (multiple)</label>
                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>

                @if ($product && $product->images->isNotEmpty())
                    <div class="row g-2 mt-2">
                        @foreach ($product->images as $img)
                            <div class="col-4 position-relative">
                                <img src="{{ asset('storage/'.$img->image) }}" class="img-fluid rounded" alt="">
                                <div class="form-check position-absolute top-0 end-0 m-1 bg-white rounded px-1">
                                    <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $img->id }}"
                                           title="Remove" id="rm{{ $img->id }}">
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">Tick an image to remove it on save.</small>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Visibility</div>
            <div class="card-body">
                @foreach (['is_featured' => 'Featured', 'is_new_arrival' => 'New Arrival', 'is_best_seller' => 'Best Seller'] as $flag => $label)
                    <div class="form-check form-switch mb-2">
                        <input type="hidden" name="{{ $flag }}" value="0">
                        <input type="checkbox" name="{{ $flag }}" value="1" class="form-check-input" id="{{ $flag }}"
                               @checked(old($flag, $product?->$flag))>
                        <label class="form-check-label" for="{{ $flag }}">{{ $label }}</label>
                    </div>
                @endforeach
                <div class="form-check form-switch mt-3">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $product?->status ?? true))>
                    <label class="form-check-label" for="status">Active (visible in shop)</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="my-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Product</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>

@push('scripts')
<script>
    // Dynamic variant rows.
    const rowTemplate = () => `
        <tr>
            <td><input type="text" name="variant_color[]" class="form-control form-control-sm"></td>
            <td><input type="text" name="variant_size[]" class="form-control form-control-sm"></td>
            <td><input type="number" step="0.01" name="variant_price[]" class="form-control form-control-sm" value="0"></td>
            <td><input type="number" name="variant_stock[]" class="form-control form-control-sm" value="0"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger removeVariant"><i class="bi bi-x"></i></button></td>
        </tr>`;

    document.getElementById('addVariant').addEventListener('click', () => {
        document.getElementById('variantRows').insertAdjacentHTML('beforeend', rowTemplate());
    });
    document.getElementById('variantRows').addEventListener('click', (e) => {
        if (e.target.closest('.removeVariant')) e.target.closest('tr').remove();
    });
</script>
@endpush
