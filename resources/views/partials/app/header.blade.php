<header class="header">
    <div class="header__container container">
        <x-app.logo class="header__brand"/>
        <nav class="main-navigation" role="navigation" aria-label="Главное меню">
            <button id="mm-button" class="main-navigation__button burger-button" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
                <span class="visually-hidden">Меню</span>
            </button>
            <ul class="main-navigation__menu">
                @foreach($mainmenu as $item)
                    <li class="main-navigation__item">
                        <a class="main-navigation__link {{ $item['activeClass'] }}"
                            href="{{ $item['link'] }}"
                        >{{ $item['title'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</header>
