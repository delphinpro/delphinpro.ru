/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

import Alpine from 'alpinejs';
import './bootstrap';

window.Alpine = Alpine;
Alpine.start();

const siteHeader = document.querySelector('.header');
siteHeader.classList.toggle('is-sticky', scrollY > 0);

window.addEventListener('scroll', () => {
    siteHeader.classList.toggle('is-sticky', scrollY > 0);
});
