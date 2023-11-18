/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

import './bootstrap';


const siteHeader = document.querySelector('.header');
siteHeader.classList.toggle('is-sticky', scrollY > 0);

window.addEventListener('scroll', () => {
    siteHeader.classList.toggle('is-sticky', scrollY > 0);
});
