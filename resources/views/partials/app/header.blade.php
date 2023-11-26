<header class="header">
    <div class="header__container container">
        <x-app.logo class="header__brand"/>
        <nav class="main-navigation">
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
