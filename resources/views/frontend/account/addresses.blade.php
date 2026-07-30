@extends('layouts.app')

@section('title', 'Addresses')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">My Addresses</h1>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.frontend.account-nav')</div>
        <div class="col-lg-9">
            <div class="row g-3 mb-4">
                @forelse ($addresses as $addr)
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body">
                                @if ($addr->is_default)<span class="badge bg-primary mb-2">Default</span>@endif
                                <p class="mb-1 fw-semibold">{{ $addr->name }} <span class="text-muted fw-normal">({{ $addr->phone }})</span></p>
                                <p class="mb-2 small">{{ $addr->line1 }} {{ $addr->line2 }}<br>{{ $addr->city }}, {{ $addr->state }} - {{ $addr->pincode }}<br>{{ $addr->country }}</p>
                                <form action="{{ route('account.addresses.destroy', $addr) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted">No saved addresses.</p></div>
                @endforelse
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Add New Address</div>
                <div class="card-body">
                    @include('partials.admin.errors')
                    <form method="POST" action="{{ route('account.addresses.store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required></div>
                            <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required></div>
                            <div class="col-12"><label class="form-label">Address Line 1 *</label><input type="text" name="line1" class="form-control" value="{{ old('line1') }}" required></div>
                            <div class="col-12"><label class="form-label">Address Line 2</label><input type="text" name="line2" class="form-control" value="{{ old('line2') }}"></div>
                            <div class="col-md-5"><label class="form-label">City *</label><input type="text" name="city" class="form-control" value="{{ old('city') }}" required></div>
                            <div class="col-md-4"><label class="form-label">State *</label><input type="text" name="state" class="form-control" value="{{ old('state') }}" required></div>
                            <div class="col-md-3"><label class="form-label">Pincode *</label><input type="text" name="pincode" class="form-control" value="{{ old('pincode') }}" required></div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
                                    <label class="form-check-label" for="isDefault">Set as default</label>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3">Add Address</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
