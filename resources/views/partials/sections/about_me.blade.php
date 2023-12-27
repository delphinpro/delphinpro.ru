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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
