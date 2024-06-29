/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

import commentBox from '@/components/comment-box';
import commentForm from '@/components/comment-form';
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
