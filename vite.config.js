import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/System/app.css',
                'resources/js/System/app.js',
                //'resources/js/System/Pages/items/main.js',
            ],
            refresh: true,
        }),
    ],
});
