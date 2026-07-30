@extends('layouts.admin')

@section('title', 'Add Product')
@section('page_title', 'Add Product')

@section('content')
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
    </form>
@endsection
