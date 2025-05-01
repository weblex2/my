<div class="w-full">
    <div wire:ignore class="w-full">
        <div id="editor"></div>
        <button wire:click="generatePdf" class="px-4 py-2 mt-4 text-white rounded bg-primary-500">
            PDF generieren
        </button>
    </div>

    @push('styles')
        <link href="https://unpkg.com/grapesjs@0.21.10/dist/css/grapes.min.css" rel="stylesheet">
        <style>
            #editor {
                height: 600px;
                width: 100%;
                border: 1px solid #ccc;
            }
            .gjs-editor {
                height: 600px !important;
                width: 100% !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/grapesjs@0.21.10/dist/grapes.min.js"></script>
        <script>
            let editor;
            document.addEventListener('DOMContentLoaded', function () {
                setTimeout(() => {
                    if (document.querySelector('#editor')) {
                        editor = grapesjs.init({
                            container: '#editor',
                            height: '600px',
                            width: '100%',
                            fromElement: false,
                            storageManager: false,
                            panels: {
                                defaults: [
                                    {
                                        id: 'basic-actions',
                                        el: '.panel__basic-actions',
                                        buttons: [
                                            { id: 'visibility', active: true, label: 'Show Borders', command: 'sw-visibility' },
                                        ],
                                    },
                                    {
                                        id: 'panel-devices',
                                        el: '.panel__devices',
                                        buttons: [
                                            { id: 'device-desktop', label: 'Desktop', command: 'set-device-desktop', active: true },
                                            { id: 'device-mobile', label: 'Mobile', command: 'set-device-mobile' },
                                        ],
                                    },
                                ],
                            },
                            blockManager: {
                                appendTo: '.panel__blocks',
                                blocks: [
                                    {
                                        id: 'text',
                                        label: 'Text',
                                        content: '<div style="padding:10px;">Hier dein Text</div>',
                                        category: 'Grundelemente',
                                    },
                                    {
                                        id: 'image',
                                        label: 'Bild',
                                        content: '<img src="https://via.placeholder.com/350x150" style="max-width: 100%;" />',
                                        category: 'Grundelemente',
                                    },
                                    {
                                        id: 'button',
                                        label: 'Button',
                                        content: '<button style="padding:10px 20px; background:#00bcd4; color:white; border:none;">Klick mich</button>',
                                        category: 'Grundelemente',
                                    },
                                ],
                            },
                            deviceManager: {
                                devices: [
                                    { name: 'Desktop', width: '' },
                                    { name: 'Mobile', width: '320px' },
                                ],
                            },
                            commands: {
                                defaults: [
                                    {
                                        id: 'set-device-desktop',
                                        run: (editor) => editor.setDevice('Desktop'),
                                    },
                                    {
                                        id: 'set-device-mobile',
                                        run: (editor) => editor.setDevice('Mobile'),
                                    },
                                ],
                            },
                        });

                        const pn = editor.Panels;
                        pn.addPanel({
                            id: 'panel-top',
                            el: '.panel__top',
                        });
                        pn.addPanel({
                            id: 'panel-blocks',
                            el: '.panel__blocks',
                        });

                        const editorCont = document.querySelector('#editor');
                        const panelTop = document.createElement('div');
                        panelTop.className = 'panel__top';
                        const panelBasicActions = document.createElement('div');
                        panelBasicActions.className = 'panel__basic-actions';
                        const panelDevices = document.createElement('div');
                        panelDevices.className = 'panel__devices';
                        const panelBlocks = document.createElement('div');
                        panelBlocks.className = 'panel__blocks';
                        editorCont.prepend(panelBlocks);
                        editorCont.prepend(panelDevices);
                        editorCont.prepend(panelBasicActions);
                        editorCont.prepend(panelTop);

                        console.log('GrapesJS Editor Initialized:', editor);

                        editor.on('update', () => {
                            const html = editor.getHtml();
                            const css = editor.getCss();
                            const fullHtml = `
                                <!DOCTYPE html>
                                <html>
                                <head>
                                    <style>${css}</style>
                                </head>
                                <body>${html}</body>
                                </html>
                            `;
                            @this.call('updateHtml', fullHtml);
                        });
                    } else {
                        console.error('Editor container #editor not found.');
                    }
                }, 500);
            });

            // Event-Listener für benutzerdefinierte Alerts
            window.addEventListener('alert', event => {
                alert(event.detail.message);
            });
        </script>
    @endpush
</div>
