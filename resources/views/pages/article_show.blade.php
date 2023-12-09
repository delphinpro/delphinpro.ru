@extends('layouts.page')

@php
    /** @var \App\Models\Article $article */
@endphp

@section('title', $article->title)

@section('meta')
    @if($article->keywords)
        <meta property="keywords" content="{{ $article->keywords }}">
    @endif
    @if($article->description)
        <meta property="description" content="{{ $article->description }}">
    @endif
@endsection

@section('og')
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:url" content="{{ route('article.show', $article) }}">
    @if($article->cover->exists)
        <meta property="og:image" content="{{ $article->cover->url }}">
    @endif
    @if($article->description)
        <meta property="og:description" content="{{ $article->description }}">
    @endif
    <meta property="article:published_time" content="{{ $article->created_at->toIso8601String() }}">
@endsection

@section('content')

    <div class="article">
        <h1>{{ $article->title }}</h1>
        <div class="article__meta">
            <div class="article__date">
                <time datetime="{{ $article->created_at }}">
                    {{ $article->created_at->format('d.m.Y H:i') }}
                </time>
            </div>
            {{--
            <div class="article__author">
                {{ $article->user->name }}
            </div>
            --}}
        </div>
        @if($article->cover->exists || $article->summary)
            <div class="article__intro">
                @if($article->cover->exists)
                    <div class="article__cover">
                        <img class="img-fluid img-thumbnail"
                            src="{!! $article->cover->url !!}"
                            alt="{{ $article->cover->name }}"
                        >
                    </div>
                @endif
                @if($article->summary)
                    <div class="article__summary">
                        {!! $article->summary !!}
                    </div>
                @endif
            </div>
        @endif
    </div>

    {!! $article->content !!}

@endsection
