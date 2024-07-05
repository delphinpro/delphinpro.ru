@props([
    'articles',
    'displayComments',
])

<?php /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Article[] $articles */ ?>

<div>
    <div class="articles-list">
        @foreach($articles as $article)
            <div class="articles-list__item">
                <article class="article-item">
                    <div class="article-item__header">
                        <h1 class="article-item__title">
                            <a href="{{ route('article.show', $article) }}">{{ $article->title }}</a>
                        </h1>
                        <div class="article-item__datebox">
                            <time class="publish-date" datetime="{{ $article->created_at->toIso8601String() }}">
                                {{ $article->created_at->format('d.m.Y') }}
                            </time>
                        </div>
                    </div>
                    @if($article->cover->exists)
                        <figure class="article-item__preview">
                            <img class="article-item__image" alt="" src="{{ $article->cover->url }}">
                        </figure>
                    @endif
                    @if($article->summary)
                        <div class="article-item__content">
                            {{ $article->summary }}
                        </div>
                    @endif
                    @if(($displayComments && $article->comments_count) || $article->tags->count())
                        <div class="article-item__footer">
                            @if($article->tags->count())
                                <div class="article-item__tags tags">
                                    <span class="tags__title">Теги:</span>
                                    @foreach($article->tags as $tag)
                                        <a class="tag" href="{{ route('article.by_tag', $tag) }}">{{ $tag->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                            @if($displayComments && $article->comments_count)
                                <div class="article-item__comments-count">
                                    {{ $article->comments_count }}
                                    {{ pluralize(['комментариев','комментарий','комментария'], $article->comments_count) }}
                                </div>
                            @endif
                        </div>
                    @endif
                </article>
            </div>
        @endforeach
    </div>
    <div class="mt-5">
        {{ $articles->links() }}
    </div>
</div>
