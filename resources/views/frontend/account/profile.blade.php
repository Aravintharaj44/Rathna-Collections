@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container">
    <h1 class="h3 mb-4">Profile &amp; Password</h1>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.frontend.account-nav')</div>
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">Profile Details</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('account.profile.update') }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-6"><label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
                            <div class="col-md-6"><label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                        </div>
                        <button class="btn btn-primary mt-3">Save Profile</button>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Change Password</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('account.password.update') }}">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                            <div class="col-md-4"><label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"></div>
                        </div>
                        <button class="btn btn-primary mt-3">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
