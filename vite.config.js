import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            output: {
                assetFileNames: 'assets/css/app.css',
                chunkFileNames: 'assets/js/app.js',
            },
            refresh: true,
        }),
    ],
});
