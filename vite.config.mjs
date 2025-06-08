import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/kb.css',
                'resources/css/gallery.css',
                'resources/css/ntfy.css',
                'resources/css/yt2mp3.css',
                'resources/js/app.js',
                'resources/js/noppal.js',
                'resources/js/reactinit.js',
                'resources/css/noppal.css',
                'resources/css/reactcv.css',
                'resources/css/futter.css',
                'resources/css/chat.css',
                'resources/css/ssc.css',
                'resources/css/maintainance.css',
                'resources/css/laravelMyAdmin.css',
                'resources/js/laravelMyAdmin.js',
                'resources/js/websocket.js',
                'resources/css/clean.css',
                'resources/css/crm.css',
            ],
            refresh: true,
        }),
    ],
});


