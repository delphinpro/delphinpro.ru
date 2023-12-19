/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2023.
 */

import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig } from 'vite';

export default defineConfig({
    resolve: {
        alias: {
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },
    plugins: [
        laravel({
            input  : [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/admin/js/dashboard.js',
            ],
            refresh: true,
        }),
    ],
});
