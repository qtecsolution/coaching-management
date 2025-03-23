import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

// Detect if running inside Docker
const isDocker = process.env.APP_DOCKER === 1;

export default defineConfig({
    server: isDocker
        ? {
            hmr: {
                host: "0.0.0.0",
            },
            port: 3000,
            host: true,
        }
        : {}, // Default settings for non-Docker

    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "public/assets/scss/app.scss",
                "public/assets/scss/pages/auth.scss",
                "public/assets/scss/pages/datatables.scss",
                "public/assets/scss/pages/error.scss",
                "public/assets/js/bootstrap.min.js",
                "public/assets/js/popper.min.js",
            ],
            refresh: true,
        }),
        vue(),
    ],
});