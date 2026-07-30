@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">Checkout</h1>

    <form action="{{ route('checkout.place') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-7">
                {{-- Saved addresses quick-fill --}}
                @if ($addresses->isNotEmpty())
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white fw-semibold">Use a saved address</div>
                        <div class="card-body">
                            @foreach ($addresses as $addr)
                                <button type="button" class="btn btn-sm btn-outline-secondary mb-1 fill-address"
                                    data-name="{{ $addr->name }}" data-phone="{{ $addr->phone }}"
                                    data-line1="{{ $addr->line1 }}" data-line2="{{ $addr->line2 }}"
                                    data-city="{{ $addr->city }}" data-state="{{ $addr->state }}" data-pincode="{{ $addr->pincode }}">
                                    {{ $addr->name }}, {{ $addr->city }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">Billing Address</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required></div>
                        <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" required></div>
                        <div class="col-12"><label class="form-label">Address Line 1 *</label><input type="text" name="line1" class="form-control" value="{{ old('line1') }}" required></div>
                        <div class="col-12"><label class="form-label">Address Line 2</label><input type="text" name="line2" class="form-control" value="{{ old('line2') }}"></div>
                        <div class="col-md-5"><label class="form-label">City *</label><input type="text" name="city" class="form-control" value="{{ old('city') }}" required></div>
                        <div class="col-md-4"><label class="form-label">State *</label><input type="text" name="state" class="form-control" value="{{ old('state') }}" required></div>
                        <div class="col-md-3"><label class="form-label">Pincode *</label><input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required></div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="hidden" name="ship_to_different" value="0">
                                <input class="form-check-input" type="checkbox" name="ship_to_different" value="1" id="shipDiff" onchange="document.getElementById('shipBox').classList.toggle('d-none', !this.checked)">
                                <label class="form-check-label" for="shipDiff">Ship to a different address</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="shipBox" class="card border-0 shadow-sm mb-4 d-none">
                    <div class="card-header bg-white fw-semibold">Shipping Address</div>
                    <div class="card-body row g-3">
                        <div class="col-md-6"><label class="form-label">Full Name</label><input type="text" name="ship_name" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="ship_phone" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Address Line 1</label><input type="text" name="ship_line1" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Address Line 2</label><input type="text" name="ship_line2" class="form-control"></div>
                        <div class="col-md-5"><label class="form-label">City</label><input type="text" name="ship_city" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label">State</label><input type="text" name="ship_state" class="form-control"></div>
                        <div class="col-md-3"><label class="form-label">Pincode</label><input type="text" name="ship_pincode" class="form-control"></div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Order Notes</div>
                    <div class="card-body">
                        <textarea name="notes" rows="2" class="form-control" placeholder="Any special instructions">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Summary + payment --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white fw-semibold">Your Order</div>
                    <div class="card-body">
                        @foreach ($items as $item)
                            <div class="d-flex justify-content-between small mb-2">
                                <span>{{ $item->product?->name }} × {{ $item->quantity }}</span>
                                <span>₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="d-flex justify-content-between mb-1"><span>Subtotal</span><span>₹{{ number_format($summary['subtotal'], 2) }}</span></div>
                        @if ($summary['discount'] > 0)<div class="d-flex justify-content-between mb-1 text-success"><span>Discount</span><span>- ₹{{ number_format($summary['discount'], 2) }}</span></div>@endif
                        <div class="d-flex justify-content-between mb-1"><span>Tax</span><span>₹{{ number_format($summary['tax'], 2) }}</span></div>
                        <div class="d-flex justify-content-between mb-1"><span>Shipping</span><span>₹{{ number_format($summary['shipping'], 2) }}</span></div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold h5"><span>Total</span><span>₹{{ number_format($summary['total'], 2) }}</span></div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold">Payment Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="razorpay" id="pmRzp" checked>
                                <label class="form-check-label" for="pmRzp">Pay Online (Razorpay)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="cod" id="pmCod">
                                <label class="form-check-label" for="pmCod">Cash on Delivery</label>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-3">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.fill-address').forEach(btn => {
        btn.addEventListener('click', () => {
            for (const key of ['name','phone','line1','line2','city','state','pincode']) {
                const el = document.querySelector(`[name="${key}"]`);
                if (el) el.value = btn.dataset[key] || '';
            }
        });
    });
</script>
@endpush
@endsection
