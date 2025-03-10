@extends('layouts.page')

<?php
/**
 * @var \App\Models\Article    $article
 * @var \App\Services\Settings $settings
 */
?>

@inject('settings', 'App\Services\Settings')

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
        <meta name="twitter:card" content="summary_large_image">
    @endif
    @if($article->description)
        <meta property="og:description" content="{{ $article->description }}">
    @endif
    <meta property="article:published_time" content="{{ $article->created_at->toIso8601String() }}">
@endsection

@section('body_class', 'line-numbers')

@push('vendor_styles')
    @vite(['resources/sass/prism.scss'])
@endpush

@push('vendor_scripts')
    <script src="{{ hashed_asset('static/prismjs/prism.js') }}"></script>
@endpush

@section('content')

    <div class="article">
        <div class="article__header">
            <h1>{{ $article->title }}</h1>
            <div class="article__meta">
                @if(!$article->published)
                    <span class="article__badge badge text-bg-danger">Не опубликовано</span>
                @endif
                <div class="article__date">
                    <time datetime="{{ $article->created_at }}">
                        {{ $article->created_at->format('d.m.Y') }}
                    </time>
                </div>
                @if($article->created_at->diffInDays($article->updated_at) > 1)
                    <div class="article__date">
                        Обновлено:
                        <time datetime="{{ $article->updated_at }}">
                            {{ $article->updated_at->format('d.m.Y') }}
                        </time>
                    </div>
                @endif
                {{--
                <div class="article__author">
                    {{ $article->user->name }}
                </div>
                --}}
                <div class="tags">
                    @foreach($article->tags as $tag)
                        <a class="tag" href="{{ route('article.by_tag', $tag) }}">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
        {{--             <div class="article__intro"> --}}
        @if($article->cover->exists)
            <div class="article__cover">
                <img
                    src="{!! $article->cover->url !!}"
                    alt="{{ $article->cover->name }}"
                >
            </div>
        @endif
        @if($article->summary)
            <div class="article__summary lead">
                {!! $article->summary !!}
            </div>
        @endif
        {{-- </div> --}}
        @if($article->cover->exists || $article->summary)
            <hr class="m-0">
        @endif
        <div class="article__content content">
            {!! $article->content !!}
        </div>
    </div>

    @if($settings->displayComments && ($settings->enableComments || $comments->isNotEmpty()))
        <div class="comments">
            @if($comments->isNotEmpty())
                <h3 class="comments__title">Комментарии ({{ $comments->count() }})</h3>
                <div class="comments__main" id="comments">
                    @foreach($comments as $comment)
                        <x-comment-box :comment="$comment"/>
                    @endforeach
                </div>
            @endif

            @if($settings->enableComments)
                <h3 class="comments__title">Вы можете оставить комментарий:</h3>
                <div class="comments__form">
                    <x-comment-form :article="$article" :isModerated="!auth()->user()?->allowCommentWithoutModerate()"/>
                </div>
            @endif
        </div>
    @endif

@endsection

@section('aside')
    @if($related->isNotEmpty())
        <div class="side-fixed">
            <div class="module">
                <div class="module-title">На эту же тему:</div>
                <ul class="side-nav">
                    @foreach($related as $article)
                        <li><a href="{{ route('article.show', $article) }}">{{ $article->title }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
@endsection
