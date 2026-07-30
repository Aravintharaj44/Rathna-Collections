@extends('layouts.app')

@section('title', ($page->meta_title ?: $page->title).' — Rathna Collections')
@section('meta_description', $page->meta_description)

@section('content')
<div class="container">
    <h1 class="h3 mb-4">{{ $page->title }}</h1>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
