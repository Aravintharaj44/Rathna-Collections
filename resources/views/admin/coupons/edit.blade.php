@extends('layouts.admin')

@section('title', 'Edit Coupon')
@section('page_title', 'Edit Coupon')

@section('content')
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
        @csrf @method('PUT')
        @include('admin.coupons._form')
    </form>
@endsection
