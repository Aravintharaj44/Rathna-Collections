@extends('layouts.admin')

@section('title', 'Add Coupon')
@section('page_title', 'Add Coupon')

@section('content')
    <form action="{{ route('admin.coupons.store') }}" method="POST">
        @csrf
        @include('admin.coupons._form')
    </form>
@endsection
