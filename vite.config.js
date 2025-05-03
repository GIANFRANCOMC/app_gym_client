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

                'resources/js/Guest/Pages/home/main.js',
                'resources/js/Guest/Pages/book_complaints/main.js',

                'resources/js/System/Pages/book_complaints/main.js',
                'resources/js/System/Pages/assets/main.js',
                'resources/js/System/Pages/assets_management/main.js',
                'resources/js/System/Pages/branches/main.js',
                'resources/js/System/Pages/companies/main.js',
                'resources/js/System/Pages/customers/main.js',
                'resources/js/System/Pages/home/main.js',
                'resources/js/System/Pages/products/main.js',
                'resources/js/System/Pages/reports/main.js',
                'resources/js/System/Pages/sales/list.js',
                'resources/js/System/Pages/sales/main.js',
                'resources/js/System/Pages/services/main.js',
                'resources/js/System/Pages/stocks_management/main.js',
                'resources/js/System/Pages/subscriptions/main.js',
                'resources/js/System/Pages/tracking_subscriptions/main.js',
                'resources/js/System/Pages/tracking_attendances/main.js',
                'resources/js/System/Pages/tracking_notifications/main.js',
                'resources/js/System/Pages/users/main.js',


                'resources/js/System/Helpers/Alerts.js',
                'resources/js/System/Helpers/Constants.js',
                'resources/js/System/Helpers/Requests.js',
                'resources/js/System/Helpers/Utils.js',
            ],
            refresh: true,
        }),
    ],
});
