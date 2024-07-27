/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

import commentBox from '@/components/comment-box.js';
import Toast from '@/toast.js';
import axios from 'axios';

class CommentForm {
    constructor(form) {
        if (form.dataset.cfInit === undefined) {
            this.mode = 'editor';
            this.form = form;
            this.actionStore = form.action;
            this.actionPreview = form.dataset.previewAction;
            this.target = document.getElementById(form.dataset.targetId);

            this.fieldset = form.querySelector('fieldset');
            this.textarea = form.querySelector('textarea[name=content]');

            this.editorBox = form.querySelector('.comment-form__editor');
            this.previewBox = form.querySelector('.comment-form__preview');

            this.btnSend = form.querySelector('.comment-form__btn-send');
            this.btnSend.addEventListener('click', this.#send.bind(this));

            this.btnPreview = form.querySelector('.comment-form__btn-preview');
            this.btnPreview.addEventListener('click', this.#showPreview.bind(this));

            form.dataset.cfInit = '';
        }
    }

    #showPreview() {

        if (this.mode === 'preview') {
            this.#toggle('editor');
            return;
        }

        this.#lock(this.btnPreview);

        axios.post(this.actionPreview, {
            content: this.textarea.value,
        }).then(res => {

            this.previewBox.innerHTML = res['content'];
            this.#toggle('preview');

        }).finally(() => {
            this.#unlock();
        });

    }

    #send() {
        this.#lock(this.btnSend);

        axios.post(this.actionStore, {
            content: this.textarea.value,
        }).then(res => {
            Toast.success(res['message'], 'Успешно', { timeout: 7000 });
            this.#clear();
            this.#toggle('editor');
            if (!this.target) {
                document.querySelector('.comments').insertAdjacentHTML(
                    'afterbegin',
                    '<h3 class="comments__title">Комментарии (1)</h3><div class="comments__main" id="comments"></div>',
                );
                this.target = document.getElementById('comments');
            }
            this.target.insertAdjacentHTML('beforeend', res['content']);
            commentBox('.comment-box');
        }).finally(() => {
            this.#unlock();
        });
    }

    #lock(el) {
        this.fieldset.disabled = true;
        if (el) el.classList.add('busy');
    }

    #unlock() {
        this.fieldset.disabled = false;
        this.form.querySelector('.busy')?.classList.remove('busy');
    }

    #toggle(mode) {
        this.mode = mode;
        this.editorBox.classList.toggle('show', mode === 'editor');
        this.previewBox.classList.toggle('show', mode === 'preview');
        this.btnPreview.innerText = mode === 'preview' ? 'Редактировать' : 'Предварительный просмотр';
    }

    #clear() {
        this.form.reset();
    }
}

export default function (selector) {
    document.querySelectorAll(selector).forEach(form => new CommentForm(form));
}
