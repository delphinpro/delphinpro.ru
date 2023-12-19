@extends('layouts.page')

@section('title', 'Публикации')

@section('content')
    @if($articles->isEmpty())
        <div class="alert alert-success">Нет опубликованных материалов</div>
    @else
        @include('pages.list')
    @endif
@endsection
