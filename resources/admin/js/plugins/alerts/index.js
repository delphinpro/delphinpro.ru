/*
 * Site delphinpro.ru
 * Copyright (c) 2024.
 */

const types = [
    { text: 'Простой текст (default)', value: 'default' },
    { text: 'Информация (info)', value: 'info' },
    { text: 'Уведомление (success)', value: 'success' },
    { text: 'Предупреждение (warning)', value: 'warning' },
    { text: 'Важная информация (danger)', value: 'danger' },
];

const getSelectedAlertBlock = editor => editor.selection.getNode().closest('.alert');

const getSelectedContent = editor => {
    const selectedContent = editor.selection.getContent().trim();
    return selectedContent.length && selectedContent.startsWith('<')
           ? selectedContent
           : `<p>${selectedContent}</p>`;
};

const insertAlertBlock = (editor, type) => {
    const node = getSelectedAlertBlock(editor);
    if (node) {
        node.className = `alert alert-${type}`;
    } else {
        editor.insertContent(`<div class="alert alert-${type}">${getSelectedContent(editor)}</div>`);
    }
};

const openDialog = editor => {
    const alert = editor.selection.getNode().closest('.alert');
    let currentType = 'default';
    let matches = /alert-(default|info|success|warning|danger)/.exec(alert?.className);
    if (alert && matches) currentType = matches[1];

    editor.windowManager.open({
        title      : 'Информационное сообщение',
        body       : {
            type : 'panel',
            items: [
                {
                    type : 'listbox',
                    name : 'type',
                    label: 'Тип сообщения',
                    items: types,
                },
            ],
        },
        buttons    : [
            {
                type: 'cancel',
                name: 'cancel',
                text: 'Cancel',
            },
            {
                type   : 'submit',
                name   : 'save',
                text   : 'Save',
                primary: true,
            },
        ],
        initialData: {
            type: currentType,
        },
        onSubmit   : api => {
            const data = api.getData();
            insertAlertBlock(editor, data.type);
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

export const AlertsPlugin = editor => {

    editor.addCommand('edit_alert', () => {
        openDialog(editor);
    });

    editor.ui.registry.addToggleButton('alerts', {
        icon    : 'warning',
        tooltip : 'Insert Alert block',
        onAction: () => editor.execCommand('edit_alert'),
        onSetup : onSetupEditable(editor, api => {
            api.setActive(editor.selection.getStart().closest('.alert') !== null);
        }),
    });

    editor.on('keydown', function (e) {
        if (e.keyCode === 27 || (e.keyCode === 13 && e.ctrlKey)) {
            const alertBlock = editor.selection ? editor.selection.getNode().closest('.alert') : null;
            if (alertBlock) {
                e.preventDefault();
                const container = alertBlock.parentNode;
                const isLast = alertBlock === container.lastChild;
                let nextElement = alertBlock.nextElementSibling;

                if (isLast) {
                    nextElement = editor.dom.create('p');
                    nextElement.innerHTML = '<br data-mce-bogus>';
                    editor.dom.insertAfter(nextElement, alertBlock);
                }

                const rng = editor.dom.createRng();
                rng.setStart(nextElement, 0);
                rng.setEnd(nextElement, 0);
                editor.selection.setRng(rng);
            }
        }
    });

    editor.on('dblclick', e => {
        if (e.target.closest('.alert')) {
            e.preventDefault();
            openDialog(editor);
        }
    });

};
