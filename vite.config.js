import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/styles.css'],
            refresh: true,
        }),
        vue()
    ],
    server: {
        host: '0.0.0.0',
        hmr: {
            host: '0.0.0.0'
        },
    },
});
