import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css',
                'resources/js/app.js',
                'resources/js/attendance.js',
                'resources/js/payroll.js',
                'resources/js/dashboard.js',
                'resources/js/settings.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
