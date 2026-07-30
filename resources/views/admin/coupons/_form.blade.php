@php($coupon = $coupon ?? null)
@include('partials.admin.errors')

<div class="card border-0 shadow-sm">
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Code <span class="text-danger">*</span></label>
            <input type="text" name="code" class="form-control text-uppercase" value="{{ old('code', $coupon?->code) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="fixed" @selected(old('type', $coupon?->type) === 'fixed')>Fixed (₹)</option>
                <option value="percent" @selected(old('type', $coupon?->type) === 'percent')>Percent (%)</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Value <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="value" class="form-control" value="{{ old('value', $coupon?->value) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Minimum Purchase (₹)</label>
            <input type="number" step="0.01" name="min_purchase" class="form-control" value="{{ old('min_purchase', $coupon?->min_purchase ?? 0) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Max Discount (₹) <small class="text-muted">for %</small></label>
            <input type="number" step="0.01" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon?->max_discount) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Usage Limit</label>
            <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon?->usage_limit) }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Expires At</label>
            <input type="date" name="expires_at" class="form-control"
                   value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d')) }}">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check form-switch">
                <input type="hidden" name="status" value="0">
                <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $coupon?->status ?? true))>
                <label class="form-check-label" for="status">Active</label>
            </div>
        </div>
    </div>
</div>

<div class="mt-4">
    <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save</button>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">Cancel</a>
</div>
