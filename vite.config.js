import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/calendar.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        minify: 'esbuild',
        target: 'es2015',
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Separate FullCalendar into its own chunk
                    if (id.includes('fullcalendar')) {
                        return 'fullcalendar';
                    }
                    // Separate node_modules into vendor chunk
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
            },
        },
        chunkSizeWarningLimit: 1000,
        sourcemap: false,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
