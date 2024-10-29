import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
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
    server: { 
        host: '0.0.0.0',
        port: 5173
    }
});
