/*
 * Site delphinpro.ru
 * Copyright (c) 2023.
 */

const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    variants: {
        extend: {
            backgroundColor: ['active'],
        },
    },
    content : [
        './app/**/*.php',
        './resources/**/*.js',
        './resources/**/*.blade.php',
    ],
    plugins : [
        require('@tailwindcss/forms'),
    ],
};
