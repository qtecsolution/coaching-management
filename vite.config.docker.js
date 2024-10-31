import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        hmr: {
            host: "0.0.0.0",
        },
        port: 3000,
        host: true,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'public/assets/scss/app.scss',
                'public/assets/scss/pages/auth.scss',
                'public/assets/scss/pages/datatables.scss',
            ],
            refresh: true,
        }),
        vue(),
    ],
});
