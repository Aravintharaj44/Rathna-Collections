@extends('layouts.admin')

@section('title', 'Edit Product')
@section('page_title', 'Edit Product')

@section('content')
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products._form')
    </form>
@endsection
