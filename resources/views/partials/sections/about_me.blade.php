<section @class(['section section-about-me', 'section_bg_strip'=>$aboutMe->strip]) style="--bg-image:url({{ asset($aboutMe->backgroundUrl) }})">
    <div class="section-about-me__wrapper">
        <div class="section__container container">
            <div class="section-about-me__main">
                <div class="row section-about-me__row">
                    <div class="col-lg-10 col-xl-9 col-xxl-8 section-about-me__content">
                        @if($aboutMe->title)
                            <h1 class="section__heading">{{ $aboutMe->title }}</h1>
                        @endif
                        {!! $aboutMe->content !!}
                        <p class="d-flex" style="gap:0.5rem 3rem;flex-wrap: wrap">
                            <span>
                                <svg width="20" height="20"><use href="#i-envelope"/></svg>
                                <a class="js-email" href="#">orpnihpled</a>
                            </span>
                            <span>
                                <svg width="20" height="20"><use href="#i-telegram"/></svg>
                                <a class="js-tg" href="#">orpnihpled</a>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
