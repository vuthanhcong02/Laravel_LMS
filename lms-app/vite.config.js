import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/common.css',
                'resources/js/app.js',
                'resources/js/dashboard.js',
                'resources/js/contact.js',
                'resources/js/mock-exam.js',
                'resources/js/vocab-games.js',
                'resources/js/hsk-index.js',
                'resources/js/hsk-take.js'
            ],
            refresh: true,
        }),
    ],
});
