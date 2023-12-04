@if($articles->isNotEmpty())
    <section class="section sectio-n_bg_strip section-articles">
        <div class="section__container container">
            <h2 class="section__heading text-center section-articles__heading">
                {{ $lastArticles['title'] ?? '' }}
            </h2>
            <div class="section__subheading text-center">
                <p>{{ $lastArticles['subtitle'] ?? '' }}</p>
            </div>
            <div class="articles-grid articles-grid_count_3">
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
        </div>
    </section>
@endif
