<section class="section s-about-me">
    <div class="section__container container">
        <div class="s-about-me__main">
            <div class="row s-about-me__row">
                <div class="col-lg-8 col-xl-7 s-about-me__content">
                    <h1 class="section__heading">
                        {{ $documentObject['longtitle'] }}
                    </h1>
                    <div class="">
                        {!! $documentObject['content'] !!}
                        {{--<div class="s-about-me__action">--}}
                        {{--    <a href="#" class="btn btn_primary-o" role="button">--}}
                        {{--        <span class="btn__content">Read more</span>--}}
                        {{--    </a>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
            @if ($documentObject['aboutMeSectionCover'])
                <div class="s-about-me__decoration d-none d-lg-flex">
                    <img class="img-responsive"
                        src="{{ $documentObject['aboutMeSectionCover'] }}"
                        role="presentation"
                        alt=""
                    >
                </div>
            @endif
        </div>
    </div>
</section>
