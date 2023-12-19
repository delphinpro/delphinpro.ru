{{-- <h1 class="page-titl-e">Все публикации</h1> --}}
{{--
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
                    --}}
{{-- <div class="article-card__tags tags">
                        <a class="tag" href="#">css</a>
                    </div> --}}{{--

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
--}}

<div class="articles-list">
    @foreach($articles as $article)
        <div class="articles-list__item">
            <article class="article-item">
                <div>
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
                {{--
            <div class="article-item__footer">
                <div class="article-item__tags tags">
                    <a class="tag" href="#">css</a>
                </div>
            </div>
--}}
            </article>
        </div>
    @endforeach
    {{-- <div class="mt-5"> --}}
    {{ $articles->links() }}
    {{-- </div> --}}
</div>
