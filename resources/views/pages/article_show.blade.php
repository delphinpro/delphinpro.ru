@extends('layouts.page')

@php
    /** @var \App\Models\Article $article */
@endphp

@section('title', $article->title)

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
