@extends('layouts.admin')

@section('title', 'Edit Page')
@section('page_title', 'Edit Page')

@section('content')
    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @csrf @method('PUT')
        @include('admin.pages._form')
    </form>
@endsection
