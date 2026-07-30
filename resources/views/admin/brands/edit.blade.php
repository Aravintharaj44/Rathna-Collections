@extends('layouts.admin')

@section('title', 'Edit Brand')
@section('page_title', 'Edit Brand')

@section('content')
    <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.brands._form')
    </form>
@endsection
