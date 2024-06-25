/*
 * Site delphinpro.ru
 * Copyright (c) 2019-2024.
 */

import laravel from 'laravel-vite-plugin';
import path from 'path';
import { defineConfig, normalizePath } from 'vite';

export default defineConfig({
    resolve: {
        alias: {
            '@'         : normalizePath(path.resolve(__dirname, 'resources/js')),
            '~bootstrap': normalizePath(path.resolve(__dirname, 'node_modules/bootstrap')),
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
