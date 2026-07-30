@extends('layouts.admin')

@section('title', 'Add Brand')
@section('page_title', 'Add Brand')

@section('content')
    <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.brands._form')
    </form>
@endsection
