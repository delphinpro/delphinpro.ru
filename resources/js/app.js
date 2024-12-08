/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

import 'viewerjs/dist/viewer.css';
import commentBox from '@/components/comment-box';
import commentForm from '@/components/comment-form';
import Toast from '@/toast';
import axios from 'axios';
import Viewer from 'viewerjs';
import './bootstrap';

//= Config

const MAINMENU_TOGGLE_BP = '768px';

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
        switch (err.response?.status) {
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
            err.response?.data.message || err.message,
            err.response?.data.title || `${err.response?.status} ${defaultTitle}`,
            { timeout: err.response?.status >= 500 ? 0 : 5000 },
        );
        return Promise.reject(err);
    },
);

const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
document.documentElement.style.setProperty('--scrollbar-width', scrollbarWidth + 'px');

if (document.getElementById('links-app')) {
    import('@/pages/links').then(module => module.default());
}


//== Sticky header

const siteHeader = document.querySelector('.header');
if (siteHeader) {
    siteHeader.classList.toggle('is-sticky', scrollY > 0);

    window.addEventListener('scroll', () => {
        siteHeader.classList.toggle('is-sticky', scrollY > 0);
    });
}


//== Main navigation

document.querySelectorAll('.main-navigation').forEach(el => {
    const isReadyClass = 'is-ready';
    const isOpenClass = 'is-open-menu';
    const overlay = document.getElementById('menu-overlay');
    const btn = el.querySelector('.main-navigation__button');
    const menu = el.querySelector('.main-navigation__menu');

    const toggleMenu = state => document.body.classList.toggle(isOpenClass, state);

    btn?.addEventListener('click', () => toggleMenu(!document.body.classList.contains(isOpenClass)));
    overlay?.addEventListener('click', () => toggleMenu(false));

    const mqMenuToggle = matchMedia(`(min-width:${MAINMENU_TOGGLE_BP})`);

    function menuToggleListener(e) {
        if (e.matches) {
            toggleMenu(false);
            overlay?.classList.remove(isReadyClass);
            menu?.classList.remove(isReadyClass);
        } else {
            setTimeout(() => {
                overlay?.classList.add(isReadyClass);
                menu?.classList.add(isReadyClass);
            }, 100);
        }
    }

    mqMenuToggle.addEventListener('change', menuToggleListener);
    menuToggleListener(mqMenuToggle);
});


//== Editors

document.querySelectorAll('.ta-comment').forEach(element => {
    element.addEventListener('keydown', function (e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;

            this.value = this.value.substring(0, start) + '    ' + this.value.substring(end);
            this.selectionStart = this.selectionEnd = start + 4;
        }
    });
});

//== Comments

window.commentForm = commentForm;
window.commentBox = commentBox;

commentForm('.comment-form');
commentBox('.comment-box');


//== Code snippets

document.querySelectorAll('.code-snippet').forEach(el => {
    const url = el.dataset.source ?? '';
    if (url.indexOf('codepen.io') !== -1) {
        let source = url.replace('/pen/', '/embed/') + '?default-tab=result&editable=true&theme-id=light';
        el.innerHTML = `<iframe src="${source}" loading="lazy" allowtransparency allowfullscreen></iframe>`;
        el.classList.add('is-init');
    }
});

//== Image Viewer

document.querySelectorAll('.img-600').forEach(img => {
    new Viewer(img, {
        toolbar: false,
        navbar : false,
    });
});
