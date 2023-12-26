@extends('layouts.page')

@section('title', 'Публикации')

@section('content')
    @if($articles->isEmpty())
        <div class="alert alert-success">Нет опубликованных материалов</div>
    @else
        <h1 class="page-title">Публикации с тегом «{{ $tag->name }}» ({{ $articles->count() }})</h1>
        @include('pages.list')
    @endif
@endsection
