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
            ],
            refresh: true,
        }),
    ],
});

