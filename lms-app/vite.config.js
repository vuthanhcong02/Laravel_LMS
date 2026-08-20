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
                'resources/js/mock-exam.js'
            ],
            refresh: true,
        }),
    ],
});
