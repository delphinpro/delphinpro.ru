/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

import axios from 'axios';
import './bootstrap';


/** @type {HTMLMetaElement} */
const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.baseURL = '/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

const siteHeader = document.querySelector('.header');
siteHeader.classList.toggle('is-sticky', scrollY > 0);

window.addEventListener('scroll', () => {
    siteHeader.classList.toggle('is-sticky', scrollY > 0);
});
