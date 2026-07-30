@extends('layouts.admin')

@section('title', 'Settings')
@section('page_title', 'Settings')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                {{-- General --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">General</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6"><label class="form-label">Site Name</label>
                            <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}"></div>
                        <div class="col-md-6"><label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}"></div>
                        <div class="col-md-6"><label class="form-label">Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}"></div>
                        <div class="col-md-6"><label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] ?? '₹' }}"></div>
                        <div class="col-12"><label class="form-label">Store Address</label>
                            <textarea name="address" rows="2" class="form-control">{{ $settings['address'] ?? '' }}</textarea></div>
                    </div>
                </div>

                {{-- Social --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Social Links</div>
                    <div class="card-body row g-3">
                        @foreach (['facebook_url' => 'Facebook', 'instagram_url' => 'Instagram', 'whatsapp_url' => 'WhatsApp', 'youtube_url' => 'YouTube'] as $key => $label)
                            <div class="col-md-6"><label class="form-label">{{ $label }}</label>
                                <input type="text" name="{{ $key }}" class="form-control" value="{{ $settings[$key] ?? '' }}"></div>
                        @endforeach
                    </div>
                </div>

                {{-- SEO --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">SEO</div>
                    <div class="card-body row g-3">
                        <div class="col-12"><label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="{{ $settings['meta_title'] ?? '' }}"></div>
                        <div class="col-12"><label class="form-label">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-control">{{ $settings['meta_description'] ?? '' }}</textarea></div>
                        <div class="col-12"><label class="form-label">Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" value="{{ $settings['meta_keywords'] ?? '' }}"></div>
                    </div>
                </div>

                {{-- Shipping / Tax --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Shipping &amp; Tax</div>
                    <div class="card-body row g-3">
                        <div class="col-md-4"><label class="form-label">Shipping Charge (₹)</label>
                            <input type="number" step="0.01" name="shipping_charge" class="form-control" value="{{ $settings['shipping_charge'] ?? 0 }}"></div>
                        <div class="col-md-4"><label class="form-label">Free Shipping Above (₹)</label>
                            <input type="number" step="0.01" name="free_shipping_above" class="form-control" value="{{ $settings['free_shipping_above'] ?? 0 }}"></div>
                        <div class="col-md-4"><label class="form-label">Default Tax (%)</label>
                            <input type="number" step="0.01" name="tax_percent" class="form-control" value="{{ $settings['tax_percent'] ?? 0 }}"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Branding</div>
                    <div class="card-body">
                        <label class="form-label">Logo</label>
                        @if (!empty($settings['logo']))
                            <img src="{{ asset('storage/'.$settings['logo']) }}" class="img-fluid rounded mb-2 bg-light p-2" alt="">
                        @endif
                        <input type="file" name="logo" class="form-control mb-3" accept="image/*">

                        <label class="form-label">Favicon</label>
                        @if (!empty($settings['favicon']))
                            <img src="{{ asset('storage/'.$settings['favicon']) }}" width="32" class="mb-2" alt="">
                        @endif
                        <input type="file" name="favicon" class="form-control" accept="image/*">
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
        </div>
    </form>
@endsection
