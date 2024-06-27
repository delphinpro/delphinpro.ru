/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

/**
 *
 * @param {string} tag
 * @param {object} attributes
 * @param {array|string|HTMLElement} children
 * @returns {HTMLElement}
 */
export function make(tag, attributes = {}, children = []) {
    const div = document.createElement(tag);

    if (tag === 'button') {
        div.setAttribute('type', 'button');
    }

    for (const [key, value] of Object.entries(attributes)) {
        if (key === 'class') {
            if (Array.isArray(value)) {
                div.classList.add(...value);
            } else if (typeof value === 'object') {
                for (const [cls, condition] of Object.entries(Object(value))) {
                    div.classList.toggle(cls, Boolean(condition));
                }
            } else {
                div.classList.add(...value.toString().split(' '));
            }
        } else {
            div.setAttribute(key, value.toString());
        }
    }

    if (!Array.isArray(children)) {
        children = [children];
    }

    for (const child of children) {
        if (typeof child === 'string' || child instanceof HTMLElement) {
            div.append(child);
        }
    }

    return div;
}

/**
 * @param {object} attributes
 * @param {array|string|HTMLElement} children
 * @returns {HTMLDivElement}
 */
export function div(attributes = {}, children = []) {
    return make('div', attributes, children);
}

/**
 * @param {object} attributes
 * @param {array|string|HTMLElement} children
 * @returns {HTMLButtonElement}
 */
export function button(attributes = {}, children = []) {
    return make('button', attributes, children);
}
