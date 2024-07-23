/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

const CSS_CLASS = 'code-snippet';

const getSelectedBlock = editor => editor.selection.getNode().closest(`.${CSS_CLASS}`);

const makeInnerContent = (url) => {
    let title = 'Sandbox';
    if (url.indexOf('codepen.io') !== -1) {
        title = 'Codepen.io';
    }
    return `<div class="code-snippet__title">${title}</div>` +
        `<a class="code-snippet__link" target="_blank" href="${url}">${url}</a>`;
};

const insertBlock = (editor, url) => {
    const node = getSelectedBlock(editor);
    if (node) {
        node.dataset.source = url;
        node.innerHTML = makeInnerContent(url);
    } else {
        editor.insertContent(`<div class="${CSS_CLASS}" data-source="${url}">${makeInnerContent(url)}</div>`);
    }
};

const openDialog = editor => {
    const sandbox = getSelectedBlock(editor);
    const url = sandbox?.dataset.source ?? '';

    editor.windowManager.open({
        title      : 'Песочница',
        body       : {
            type : 'panel',
            items: [
                {
                    type     : 'input',
                    name     : 'url',
                    label    : 'Ссылка на песочницу',
                    maximized: true,
                },
            ],
        },
        buttons    : [
            {
                type: 'cancel',
                name: 'cancel',
                text: 'Отмена',
            },
            {
                type   : 'submit',
                name   : 'save',
                text   : 'Сохранить',
                primary: true,
            },
        ],
        initialData: {
            url,
        },
        onSubmit   : api => {
            const data = api.getData();
            insertBlock(editor, data.url);
            api.close();
        },
    });
};

const onSetupEditable = (editor, onChanged = () => {}) => api => {
    const nodeChanged = () => {
        api.setEnabled(editor.selection.isEditable());
        onChanged(api);
    };
    editor.on('NodeChange', nodeChanged);
    nodeChanged();
    return () => {
        editor.off('NodeChange', nodeChanged);
    };
};

export const SandboxPlugin = editor => {
    editor.on('PreProcess', e => {
        const divs = editor.dom.select(`div.${CSS_CLASS}[contenteditable=false]`, e.node);
        tinymce.util.Tools.each(divs, elm => {
            editor.dom.setAttrib(elm, 'contentEditable', null);
            editor.dom.setAttrib(elm, 'data-mce-highlighted', null);
        });
    });
    editor.on('PreInit', () => {
        editor.parser.addNodeFilter('div', nodes => {
            for (let node of nodes) {
                const cls = (node.attr('class') ?? '') + ' ';
                const is = (cls.indexOf(CSS_CLASS + ' ') !== -1);
                if (is) {
                    node.attr('contenteditable', 'false');
                    node.attr('data-mce-highlighted', 'false');
                }
            }
        });
    });


    editor.addCommand('edit_sandbox', () => {
        openDialog(editor);
    });

    editor.ui.registry.addToggleButton('sandbox', {
        icon    : 'embed-page',
        tooltip : 'Insert Code Sandbox',
        onAction: () => editor.execCommand('edit_sandbox'),
        onSetup : onSetupEditable(editor, api => {
            api.setActive(editor.selection.getStart().closest(`.${CSS_CLASS}`) !== null);
        }),
    });

    editor.on('dblclick', e => {
        if (e.target.closest(`.${CSS_CLASS}`)) {
            e.preventDefault();
            openDialog(editor);
        }
    });

};
