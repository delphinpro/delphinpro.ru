@extends('layouts.page')

@section('title', 'Публикации')

@section('content')
    @if($articles->isEmpty())
        <div class="alert alert-success">Нет опубликованных материалов</div>
    @else
        <h1 class="page-title">Все публикации</h1>
        <div class="articles-grid">
            @foreach($articles as $article)
                <div class="articles-grid__item">
                    <div class="article-card">
                        <figure class="article-card__preview">
                            <img class="article-card__image" alt="" src="{{ $article->cover->url }}">
                        </figure>
                        <div class="article-card__content">
                            <h3 class="article-card__title">
                                <a href="{{ route('article.show', $article) }}">{{ $article->title }}</a>
                            </h3>
                            <p>{{ $article->summary }}</p>
                        </div>
                        <div class="article-card__footer">
                            {{-- <div class="article-card__tags tags">
                                <a class="tag" href="#">css</a>
                            </div> --}}
                            <div class="article-card__datebox">
                                <time class="publish-date" datetime="{{ $article->created_at }}">
                                    {{ $article->created_at->format('d.m.Y') }}
                                </time>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-5">
            {{ $articles->links() }}
        </div>
    @endif
@endsection
