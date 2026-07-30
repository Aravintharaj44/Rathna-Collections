@extends('layouts.admin')

@section('title', 'Add Page')
@section('page_title', 'Add Page')

@section('content')
    <form action="{{ route('admin.pages.store') }}" method="POST">
        @csrf
        @include('admin.pages._form')
    </form>
@endsection
