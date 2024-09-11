import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/Tenant/app.css',
                'resources/js/Tenant/app.js',
                'resources/js/Tenant/Pages/items/main.js',
            ],
            refresh: true,
        }),
    ],
});
