<footer class="footer">
    <div class="container">
        <div class="row justify-content-between">
            <div class="footer__column col-md-4 footer-widget">
                <x-app.logo class="footer__brand footer-widget__header"/>
                <div class="footer-widget__content footer-widget__info">
                    {{-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. At aut beatae cupiditate
                        distinctio doloribus et fugit id maiores optio, pariatur praesentium quo ullam ut?
                        Aperiam dolores harum nesciunt optio similique?</p> --}}
                    <p>©&nbsp;2011&ndash;{{ date('Y') }}&nbsp;delphinpro</p>
                </div>
            </div>
            <div class="footer__column col-md-auto footer-widget">
                {{-- <div class="footer-widget__header">
                    <h3 class="footer-widget__title">Quick links</h3>
                </div>
                <div class="footer-widget__content">
                    <ul class="footer-list">
                        <li><a href="#">About me</a></li>
                        <li><a href="#">Terms of service</a></li>
                        <li><a href="#">Privacy policy</a></li>
                    </ul>
                </div> --}}
            </div>
            <div class="footer__column col-md-auto footer-widget">
                <div class="footer-widget__header">
                    <h3 class="footer-widget__title">Контакты</h3>
                </div>
                <div class="footer-widget__content">
                    <ul class="footer-list">
                        <li>
                            <svg width="20" height="20"><use href="#i-envelope"/></svg>
                            <a id="footer-email" href="#">orpnihpled</a>
                        </li>
                        <li>
                            <svg width="20" height="20"><use href="#i-telegram"/></svg>
                            <a id="footer-tg" href="#">orpnihpled</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<script>
    (() => {
        const a = document.getElementById('footer-email');
        if (a) {
            let e = a.textContent.trim().split('').reverse();
            e.push(['@', 'yandex', '.', 'ru'].join(''));
            a.textContent = e.join('');
            a.href = 'mailto:' + a.textContent;
        }
        const t = document.getElementById('footer-tg');
        if (t) {
            t.textContent = t.textContent.trim().split('').reverse().join('');
            t.href = 'https://t.me/' + t.textContent;
        }
    })();
</script>
