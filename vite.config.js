import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', '@inertiajs/vue3'],
                },
            },
        },
        chunkSizeWarningLimit: 1000,
    },
    optimizeDeps: {
        include: ['vue', '@inertiajs/vue3'],
    },
    server: {
        host: 'localhost',
        port: 5173,
        cors: true,
    },
});
