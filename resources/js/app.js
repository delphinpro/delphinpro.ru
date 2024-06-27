/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

import Toast from '@/toast';
import axios from 'axios';
import './bootstrap';

//== PREPARE

/** @type {HTMLMetaElement} */
const token = document.head.querySelector('meta[name="csrf-token"]');
axios.defaults.baseURL = '/';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;

axios.interceptors.response.use(
    function (response) {
        return response.data;
    },
    function (err) {
        let defaultTitle;
        switch (err.response.status) {
            case 403:
                defaultTitle = 'Запрещено';
                break;
            case 404:
                defaultTitle = 'Не найдено';
                break;
            default:
                defaultTitle = 'Ошибка';
        }
        Toast.error(
            err.response.data.message || err.message,
            err.response.data.title || `${err.response.status} ${defaultTitle}`,
            { timeout: err.response.status >= 500 ? 0 : 5000 },
        );
        return Promise.reject(err);
    },
);


//== Sticky header

const siteHeader = document.querySelector('.header');
siteHeader.classList.toggle('is-sticky', scrollY > 0);

window.addEventListener('scroll', () => {
    siteHeader.classList.toggle('is-sticky', scrollY > 0);
});
