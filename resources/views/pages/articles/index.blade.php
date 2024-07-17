<?php /** @var \App\Services\Settings $settings */ ?>

@inject('settings', 'App\Services\Settings')

@extends('layouts.page')

@section('title', 'Публикации')

@section('meta')
    @if(($_GET['page']??null)==='1')
        <link rel="canonical" href="{{ route('article.index') }}">
    @endif
@endsection

@section('content')
    @if($articles->isEmpty())
        <div class="alert">Нет опубликованных материалов</div>
    @else
        <h1 class="page-title">Все публикации</h1>
        <x-articles-list :articles="$articles" :displayComments="$settings->displayComments"/>
    @endif
@endsection
