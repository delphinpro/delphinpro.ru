/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

let toastId = 0;
let previousToast;

const CLASSES = {
    containerId  : 'toast-container',
    wrapperClass : 'toast__wrapper',
    toastClass   : 'toast',
    titleClass   : 'toast__title',
    messageClass : 'toast__message',
    closeClass   : 'toast__closer',
    progressClass: 'toast__progress',
    typeClasses  : {
        info   : 'toast_info',
        success: 'toast_success',
        warning: 'toast_warning',
        error  : 'toast_error',
    },
};

let defaults = {
    position         : 'top-right',
    closeButton      : true,
    closeHtml        : '<button type="button">&times;</button>',
    progressBar      : false,
    timeout          : 5000,
    stayOnHover      : true,
    escapeHtml       : true,
    target           : 'body',
    preventDuplicates: false,
    closeOnClick     : true,
    newestOnTop      : true,

    showDuration: 400,
    hideDuration: 500,
};

function makeDiv(attributes = {}) {
    const div = document.createElement('div');
    for (const [key, value] of Object.entries(attributes)) {
        if (key === 'class') {
            if (Array.isArray(value)) {
                div.classList.add(...value);
            } else if (typeof value === 'object') {
                for (const [cls, condition] of Object.entries(Object(value))) {
                    div.classList.toggle(cls, Boolean(condition));
                }
            } else {
                div.classList.add(value.toString());
            }
        } else {
            div.setAttribute(key, value.toString());
        }
    }
    return div;
}

/**
 * @param {string} html
 * @returns {HTMLElement}
 */
function makeElementFromHtml(html) {
    return (new DOMParser()).parseFromString(html, 'text/html').body.firstElementChild;
}


export default class Toast {
    // noinspection JSUnusedGlobalSymbols
    static TOP_LEFT = 'top-left';
    // noinspection JSUnusedGlobalSymbols
    static TOP_RIGHT = 'top-right';
    // noinspection JSUnusedGlobalSymbols
    static BOTTOM_LEFT = 'bottom-left';
    // noinspection JSUnusedGlobalSymbols
    static BOTTOM_RIGHT = 'bottom-right';

    static INFO = 'info';
    static SUCCESS = 'success';
    static WARNING = 'warning';
    static ERROR = 'error';

    version = '1.0.0';
    #options = {};
    /** @type {null|HTMLDivElement} */
    #toastElement = null;
    /** @type {null|HTMLDivElement} */
    #titleElement = null;
    /** @type {null|HTMLDivElement} */
    #messageElement = null;
    /** @type {null|HTMLDivElement} */
    #progressElement = null;
    /** @type {null|HTMLElement} */
    #closeElement = null;
    #message;
    #title;
    #closeTimeoutId = null;
    #type = '';

    constructor(options = {}) {
        this.#options = Object.assign({}, defaults, options);
    }

    // noinspection JSUnusedGlobalSymbols
    static setDefaults(options) {
        defaults = Object.assign({}, defaults, options);
    }

    static info(message, title = '', options = {}) {
        return new Toast(options).notify(message, title, Toast.INFO);
    }

    static success(message, title = '', options = {}) {
        return new Toast(options).notify(message, title, Toast.SUCCESS);
    }

    static warning(message, title = '', options = {}) {
        return new Toast(options).notify(message, title, Toast.WARNING);
    }

    static error(message, title = '', options = {}) {
        return new Toast(options).notify(message, title, Toast.ERROR);
    }

    notify(message, title, type = '') {
        if (this.#shouldExit(message)) return;

        toastId++;

        this.#type = type;
        this.#message = message;
        this.#title = title;

        this.progressBar = {
            intervalId : null,
            hideEta    : null,
            maxHideTime: null,
            paused     : false,
        };

        this.#getToastContainer();
        this.#makeToast(type);

        this.#displayToast();
        this.#handleEvents();
    }

    #shouldExit(message) {
        if (this.#options.preventDuplicates) {
            if (message === previousToast) {
                return true;
            } else {
                previousToast = message;
            }
        }
        return false;
    }

    #getToastContainer() {
        let container = document.getElementById(CLASSES.containerId);
        if (container) return container;

        container = makeDiv({
            id   : CLASSES.containerId,
            class: `toast-${this.#options.position}`,
        });

        document.querySelector(this.#options.target).appendChild(container);

        return container;
    }

    #makeToast(type = '') {
        this.#toastElement = makeDiv({ class: CLASSES.toastClass });

        this.#setType(type);
        this.#setTitle();
        this.#setContent();
        this.#setCloseButton();
        this.#setProgressBar();
        this.#setAria();
    }

    #setType(type = '') {
        if (type && CLASSES.typeClasses[type]) {
            this.#toastElement.classList.add(CLASSES.typeClasses[type]);
        }
    }

    #setTitle() {
        if (this.#title) {
            this.#titleElement = makeDiv({ class: CLASSES.titleClass });
            const property = this.#options.escapeHtml ? 'innerText' : 'innerHTML';
            this.#titleElement[property] = this.#title;
            this.#toastElement.append(this.#titleElement);
        }
    }

    #setContent() {
        if (this.#message) {
            this.#messageElement = makeDiv({ class: CLASSES.messageClass });
            const property = this.#options.escapeHtml ? 'innerText' : 'innerHTML';
            this.#messageElement[property] = this.#message;
            this.#toastElement.append(this.#messageElement);
        }
    }

    #setCloseButton() {
        if (this.#options.closeButton) {
            this.#closeElement = makeElementFromHtml(this.#options.closeHtml);
            this.#closeElement.classList.add(CLASSES.closeClass);
            this.#closeElement.setAttribute('role', 'button');
            this.#toastElement.prepend(this.#closeElement);

            this.#closeElement.addEventListener('click', event => {
                event.stopPropagation();
                this.#hideToast();
            }, { once: true });
        }
    }

    #setProgressBar() {
        if (this.#options.progressBar && this.#options.timeout) {
            this.#progressElement = makeDiv({ class: CLASSES.progressClass });
            this.#toastElement.append(this.#progressElement);
            this.#startProgressTimer();
        }
    }

    #setAria() {
        let ariaValue;
        switch (this.#type) {
            case Toast.SUCCESS:
            case Toast.INFO:
            case '':
                ariaValue = 'polite';
                break;
            default:
                ariaValue = 'assertive';
        }
        this.#toastElement.setAttribute('aria-live', ariaValue);
    }


    #displayToast() {
        const container = this.#getToastContainer();
        container[this.#options.newestOnTop ? 'prepend' : 'append'](this.#toastElement);

        this.#toastElement.style.marginTop = `-${this.#toastElement.clientHeight}px`;

        setTimeout(() => {
            this.#toastElement.style.transition = `all ${this.#options.showDuration}ms ease`;
            this.#toastElement.style.marginTop = '0';

            if (this.#options.timeout > 0) {
                this.#startCloseTimer();
            }
        }, 1);
    }

    #hideToast() {
        const container = this.#getToastContainer();

        this.#toastElement.style.pointerEvents = `none`;
        this.#toastElement.style.transitionDuration = `${this.#options.hideDuration}ms`;

        this.#toastElement.style.marginTop = `-${this.#toastElement.clientHeight}px`;
        this.#toastElement.style.opacity = '0';

        setTimeout(() => {
            this.#toastElement.style.display = 'none';
            this.#toastElement.remove();
            clearInterval(this.progressBar.intervalId);
            clearTimeout(this.#closeTimeoutId);
            if (!container.children.length) {
                container.remove();
            }
        }, this.#options.hideDuration);
    }

    #handleEvents() {
        if (this.#options.stayOnHover && this.#options.timeout > 0) {
            this.#toastElement.addEventListener('mouseenter', () => {
                clearInterval(this.progressBar.intervalId);
                clearTimeout(this.#closeTimeoutId);
            });
            this.#toastElement.addEventListener('mouseleave', () => {
                this.#startCloseTimer();
                if (this.#options.progressBar && this.#options.timeout) {
                    this.#startProgressTimer();
                }
            });
        }

        if (this.#options.closeOnClick) {
            this.#toastElement.addEventListener('click', this.#hideToast.bind(this), { once: true });
        }
    }

    #startCloseTimer() {
        this.#closeTimeoutId = setTimeout(this.#hideToast.bind(this), this.#options.timeout);
    }

    #startProgressTimer() {
        this.progressBar.maxHideTime = parseFloat(this.#options.timeout);
        this.progressBar.hideEta = new Date().getTime() + this.progressBar.maxHideTime;
        this.progressBar.intervalId = setInterval(() => {
            const percentage = ((this.progressBar.hideEta - (new Date().getTime())) / this.progressBar.maxHideTime) * 100;
            this.#progressElement.style.width = (percentage + '%');
        }, 10);
    }
}
