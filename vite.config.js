import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/kitchen.js',
                'resources/js/DishStatusUpdated.js',
                'resources/css/kitchen.css',
                'resources/js/posTable.js', 
                'resources/js/orderItem.js'
            ],
            refresh: true,
        }),
    ],
});
