import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path'; 

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/user/pages/home.js',
                'resources/js/user/pages/contact.js',
                'resources/js/user/pages/about.js',
                'resources/js/user/pages/product-detail.js',
                'resources/js/user/auth/forgot-password.js',
                'resources/js/user/auth/login.js',
                'resources/js/user/auth/register.js',
                'resources/js/user/auth/reset-password.js',
                'resources/js/user/auth/check-email.js',
                'resources/js/user/checkout/checkout.js',
                'resources/js/user/customer-support/order-history.js',
                'resources/js/user/customer-support/order-tracking.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@css': path.resolve(__dirname, 'resources/css'),
            '@js': path.resolve(__dirname, 'resources/js'),
        },
    },
    server: {
        host: '127.0.0.1',
        strictPort: true,
        hmr: { host: '127.0.0.1' },
    },
});
