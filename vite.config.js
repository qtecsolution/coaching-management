import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['public/assets/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
