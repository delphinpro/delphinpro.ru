@if($articles->articles->isNotEmpty())
    <section @class(['section section-articles', 'section_bg_strip'=>$articles->strip])>
        <div class="section__container container">
            <h2 class="section__heading text-center section-articles__heading">
                {{ $articles->title }}
            </h2>
            <div class="section__subheading text-center">
                <p>{{ $articles->subtitle }}</p>
            </div>
            <div class="articles-grid articles-grid_count_3">
                @foreach($articles->articles as $article)
                    <div class="articles-grid__item">
                        <article class="article-card">
                            <h2 class="article-card__title">
                                <a href="{{ route('article.show', $article) }}">{{ $article->title }}</a>
                            </h2>
                            @if($article->cover->exists)
                                <a href="{{ route('article.show', $article) }}" class="article-card__preview">
                                    <img class="article-card__image" alt="" src="{{ $article->cover->url }}">
                                </a>
                            @endif
                            <div class="article-card__content">
                                {{ $article->summary }}
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
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
