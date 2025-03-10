<?php /** @var \App\Services\Settings $settings */ ?>

@inject('settings', 'App\Services\Settings')

@extends('layouts.page')

@section('title', 'Публикации')

@section('content')
    @if($articles->isEmpty())
        <div class="alert">Нет опубликованных материалов</div>
    @else
        <h1 class="page-title">Публикации с меткой «{{ $tag->name }}» ({{ $articles->count() }})</h1>
        <x-articles-list :articles="$articles" :displayComments="$settings->displayComments"/>
    @endif
@endsection
