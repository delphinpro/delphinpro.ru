<section class="section s-page-intro"
    @if ($documentObject['introBackground']) style="background-image:url({{ $documentObject['introBackground'] }})" @endif
>
    <div class="section__overlay s-page-intro__overlay"></div>
    <div class="s-page-intro__container container-fluid">
        <div class="s-page-intro__content">
            @if ($documentObject['introTitle'])
                <div class="s-page-intro__title">{{ $documentObject['introTitle'] }}</div>
            @endif
            @if ($documentObject['introSubtitle'])
                <div class="s-page-intro__text">{{ $documentObject['introSubtitle'] }}</div>
            @endif
            <div class="s-page-intro__action">
                <button class="btn btn_primary"><span class="btn__content">Get a quote</span></button>
            </div>
        </div>
    </div>
</section>
