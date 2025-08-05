import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    server: {
        host: true, // 👈 allow Docker to bind to 0.0.0.0
        port: 5173,
        strictPort: true,
        watch: {
            usePolling: true, // 👈 Required for Docker + file change detection
        },
        proxy: {
            '/api': {
                target: 'http://app:9000',
                changeOrigin: true,
                rewrite: (path) => path.replace(/^\/api/, '/api'),
            },
        },
    },
    plugins: [
        laravel({
            input: ['resources/js/app.ts', 'resources/css/app.css'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                app: 'resources/js/app.ts',
                ssr: 'resources/js/ssr.ts',
            },
        },
    },
});
