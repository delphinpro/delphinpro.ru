/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

import Dialog from '@/components/dialog';
import Toast from '@/toast.js';
import axios from 'axios';

class CommentBox {
    #buttons = [];

    constructor(el) {
        if (el.dataset.cfInit === undefined) {
            this.el = el;

            this.btnDelete = el.querySelector('.comment-box__btn-delete');
            this.btnDelete?.addEventListener('click', this.#deleteComment.bind(this));

            this.btnPublish = el.querySelector('.comment-box__btn-publish');
            this.btnPublish?.addEventListener('click', this.#publishComment.bind(this));

            this.#buttons = [this.btnPublish, this.btnDelete].filter(e => e);
            el.dataset.cfInit = '';
        }
    }

    async #deleteComment() {
        const confirmed = await Dialog.confirm({
            content : 'Вы действительно хотите удалить этот комментарий?',
            actionOk: { text: 'Да, удалить' },
        });

        if (!confirmed) {
            return;
        }

        this.#lock();

        axios.delete(this.btnDelete.dataset.request).then(res => {
            Toast.success(res['message'], 'Успешно', { timeout: 3000 });
            this.el.remove();
        }).catch(() => this.#unlock());
    }

    async #publishComment() {
        const confirmed = await Dialog.confirm({
            type    : Dialog.TYPE_SUCCESS,
            content : 'Вы действительно хотите опубликовать этот комментарий?',
            actionOk: { text: 'Да, опубликовать', type: Dialog.ACTION_TYPE_SUCCESS },
        });

        if (!confirmed) {
            return;
        }

        this.#lock();

        axios.post(this.btnPublish.dataset.request).then(res => {
            Toast.success(res['message'], 'Успешно', { timeout: 3000 });
            this.el.classList.remove('is-moderate');
        }).catch(() => {}).finally(() => this.#unlock());
    }

    #lock() {
        this.#buttons.forEach(btn => {
            btn.disabled = true;
            btn.classList.add('busy');
        });
    }

    #unlock() {
        this.#buttons.forEach(btn => {
            btn.disabled = false;
            btn.classList.remove('busy');
        });
    }
}

export default function (selector) {
    document.querySelectorAll(selector).forEach(el => new CommentBox(el));
}
