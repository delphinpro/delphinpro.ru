/*
 * Site delphinpro.ru
 * Copyright (c) 2023-2024.
 */

import { AlertsPlugin } from '@admin/plugins/alerts';
import { CodeSamplePlugin } from '@admin/plugins/codesample';
import { SandboxPlugin } from '@admin/plugins/sandbox';

function getToken() {
    return document.getElementById('csrf_token')?.content;
}

function addMonospaceButton(editor) {
    editor.ui.registry.addToggleButton('monospace', {
        text    : 'M',
        onAction: () => editor.execCommand('mceToggleFormat', false, 'code'),
        onSetup : api => {
            api.setActive(editor.formatter.match('code'));
            const changed = editor.formatter.formatChanged('code', state => api.setActive(state));
            return () => changed.unbind();
        },
    });
}

function saveContent(editor, url) {
    const body = new FormData();
    body.append('_token', getToken());
    body.append('content', editor.getContent());

    fetch(url, { method: 'POST', body })
        .then(res => res.json())
        .then(res => console.log(res))
        .catch(err => console.log(err));
}

tinymce.PluginManager.add('codesample', CodeSamplePlugin);
tinymce.PluginManager.add('alerts', AlertsPlugin);
tinymce.PluginManager.add('sandbox', SandboxPlugin);

/**
 * @var {object} Controller
 */
export default class extends Controller {

    static targets = [
        'textarea',
    ];

    connect() {
        const savingUrl = this.textareaTarget.dataset.savingUrl;

        // noinspection JSUnusedGlobalSymbols
        tinymce.init({
            target: this.textareaTarget,

            setup(editor) {
                addMonospaceButton(editor);
            },

            plugins: [
                'alerts',
                'sandbox',
                'code',
                'fullscreen',
                'table',
                'lists',
                'image',
                'codesample',
                'wordcount',
                'visualchars',
                'visualblocks',
                'link',
                'save',
                'autosave',
            ],

            toolbar_sticky       : true,
            toolbar_sticky_offset: 60,

            toolbar: [
                [
                    savingUrl ? 'save' : '',
                    'code fullscreen',
                    'restoredraft',
                    'undo redo',
                    'table',
                    'alerts sandbox',
                    'image codesample hr',
                    'visualblocks visualchars wordcount',
                ].filter(s => s).join('|'),
                [
                    'h2 h3',
                    'bold italic underline strikethrough monospace',
                    'link unlink openlink',
                    'blockquote',
                    'superscript subscript',
                    'bullist numlist',
                    'align',
                ].join('|'),
            ],

            save_onsavecallback: () => {
                saveContent(tinymce.activeEditor, savingUrl);
                return false;
            },

            relative_urls     : false,
            remove_script_host: true,

            images_upload_url: '/admin/upload',
            image_caption    : true,
            image_title      : true,
            image_dimensions : false,
            typeahead_urls   : false,
            images_file_types: 'jpeg,jpg,png,gif,svg,webp',
            image_class_list : [
                { title: 'None', value: '' },
                { title: 'No border', value: 'img_no_border' },
                { title: 'Green border', value: 'img_green_border' },
                { title: 'Blue border', value: 'img_blue_border' },
                { title: 'Red border', value: 'img_red_border' },
            ],

            codesample_languages: [
                { text: 'Plain text', value: 'none' },
                { text: 'HTML/XML', value: 'markup' },
                { text: 'JavaScript', value: 'javascript' },
                { text: 'TypeScript', value: 'typescript' },
                { text: 'PHP', value: 'php' },
                { text: 'CSS', value: 'css' },
                { text: 'Scss', value: 'scss' },
                { text: 'Less', value: 'less' },
                { text: 'Shell', value: 'bash' },
                { text: 'Diff', value: 'diff' },
                { text: 'SQL', value: 'sql' },
                { text: 'Apache', value: 'apacheconf' },
                { text: 'Nginx', value: 'nginx' },
                { text: 'Batch', value: 'batch' },
                { text: 'Markdown', value: 'markdown' },
                { text: 'Twig', value: 'twig' },
                { text: 'Yaml', value: 'yaml' },
                { text: 'C-like', value: 'clike' },
                { text: 'Treeview', value: 'treeview' },
            ],

            autosave_retention: (60 * 24 * 7) + 'm',

            contextmenu: false,

            content_css: '/static/tinymce.css',
            // skin       : 'oxide-dark',
            skin    : 'tinymce-5-dark',
            menubar : false,
            branding: false,
            height  : 700,
        });
    }

    disconnect() {
        tinymce.remove();
    }
}
