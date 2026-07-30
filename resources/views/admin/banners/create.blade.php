@extends('layouts.admin')

@section('title', 'Add Banner')
@section('page_title', 'Add Banner')

@section('content')
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.banners._form')
    </form>
@endsection
