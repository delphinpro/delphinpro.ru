<section class="section section-intro"
    style="background-image:url({{ $intro['background'] ?? '' }})"
>
    <div class="section__overlay section-intro__overlay"></div>
    <div class="section-intro__container container-fluid">
        <div class="section-intro__content">
            <div class="section-intro__title">{{ $intro['title'] ?? '' }}</div>
            <div class="section-intro__text">
                {{ $intro['subtitle'] ?? '' }}
            </div>
        </div>
    </div>
</section>
