/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

import { button, div } from '@/dom.js';
import Modal from '~bootstrap/js/src/modal.js';

// noinspection JSUnusedGlobalSymbols
export default class Dialog {
    static TYPE_SUCCESS = 'modal-success';
    static TYPE_WARNING = 'modal-warning';

    static ACTION_TYPE_CANCEL = 'secondary';
    static ACTION_TYPE_SUCCESS = 'success';
    static ACTION_TYPE_WARNING = 'danger';

    constructor(options = {}) {
        console.log(options);
        this.result = null;
        this.options = {
            type   : '',
            title  : '',
            content: '',
            isModal: false,
            actions: [],
            ...options,
        };

        this.actionElements = [];

        this.#create();
    }

    /**
     * @typedef {{type:string,text:string,callback:function}} ConfirmDialogAction
     * @param {{type:string|undefined,title:string|undefined,content:string,isModal:boolean,actionOk:ConfirmDialogAction|undefined,actionCancel:ConfirmDialogAction|undefined}} options
     * @returns {Dialog}
     */
    static confirm(options) {
        const dialog = new Dialog({
            type      : Dialog.TYPE_WARNING,
            title     : 'Подтвердите действие',
            isModal   : true,
            closeOnEsc: false,
            ...options,
            actions: [
                {
                    type    : Dialog.ACTION_TYPE_WARNING,
                    text    : 'OK',
                    callback: () => true,
                    ...(options.actionOk || {}),
                }, {
                    type    : Dialog.ACTION_TYPE_CANCEL,
                    text    : 'Отмена',
                    callback: () => false,
                    ...(options.actionCancel || {}),
                },
            ],
        });

        return dialog.show();
    }

    show() {
        this.titleElement.innerHTML = this.options.title;
        this.bodyElement.innerHTML = this.options.content;

        this.modal.show();

        this.modalElement.addEventListener('hidden.bs.modal', () => {
            this.modalElement.remove();
        });

        return new Promise(resolve => {
            this.modalElement.addEventListener('hide.bs.modal', () => {
                resolve(this.result);
            });
        });
    }

    #create() {
        this.contentElement = div({ class: 'modal-content shadow-lg' }, [
            this.#makeHeader(),
            this.#makeBody(),
            this.#makeFooter(),
        ]);
        this.modalElement = div({ class: 'modal fade', tabindex: -1 }, [
            div({ class: 'modal-dialog' }, this.contentElement),
        ]);

        if (this.options.type) {
            this.contentElement.classList.add(this.options.type);
        }

        document.body.append(this.modalElement);

        this.modal = new Modal(this.modalElement, {
            backdrop: this.options.isModal ? 'static' : true,
            keyboard: this.options.closeOnEsc,
        });
    }

    #makeHeader() {
        this.titleElement = div({ class: 'modal-title' }, this.options.title);

        const icon = '<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\' fill=\'currentColor\'><path d=\'M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z\'/></svg>';
        const closer = button({ class: 'btn-close', 'data-bs-dismiss': 'modal', 'aria-label': 'Закрыть' });
        closer.innerHTML = icon;

        return div({ class: 'modal-header' }, [this.titleElement, closer]);
    }

    #makeBody() {
        this.bodyElement = div({ class: 'modal-body' }, this.options.content);
        return this.bodyElement;
    }

    #makeFooter() {
        for (const action of this.options.actions) {
            const btn = button({ class: 'btn btn-' + action.type, type: 'button' }, action.text);

            btn.addEventListener('click', () => {
                this.result = action.callback();
                this.modal.hide();
            }, { once: true });

            this.actionElements.push(btn);
        }

        return div({ class: 'modal-footer' }, this.actionElements);
    }
}
