import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/vue/admins/admins/main.js',
                'resources/js/vue/admins/branches/main.js',
                'resources/js/vue/admins/customers/main.js',
                'resources/js/vue/admins/home/main.js',
                'resources/js/vue/admins/productServices/main.js',
            ],
            refresh: true,
        }),
    ],
});
