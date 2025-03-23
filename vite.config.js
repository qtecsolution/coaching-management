import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';

// Detect if running inside Docker
const isDocker = process.env.APP_DOCKER === '1';

export default defineConfig({
    // Server configuration based on Docker environment
    server: {
        host: isDocker ? '0.0.0.0' : 'localhost',  // In Docker use 0.0.0.0, else localhost for local dev
        port: 3000, // Ensure this matches the Docker expose ports
        hmr: {
            host: isDocker ? '0.0.0.0' : 'localhost',  // Enable hot module reload for Docker
        },
        strictPort: true, // Enforce using the specified port (3000)
        watch: {
            usePolling: true, // Helps when using Docker (sometimes fixes file change issues)
        },
    },

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
