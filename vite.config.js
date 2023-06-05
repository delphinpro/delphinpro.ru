/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input  : ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
