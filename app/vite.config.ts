import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    server: {
            host: '0.0.0.0', // Allow Docker to bind to 0.0.0.0
            port: 5173,
            strictPort: true,
            // watch: {
            // usePolling: false, // Required for Docker + file change detection
            // //   interval: 100,
            //     ignored: ['**/node_modules/**', '**/vendor/**', '**/storage/**', '**/.git/**',],
            // },
            hmr: {
                host: '127.0.0.1',
                port: 5173,
            },
            proxy: {
                '/api': {
                    target: 'http://host.docker.internal:8000',
                    // target: 'http://tennis-php-fpm:8000', container
                    changeOrigin: true,
                    rewrite: (path) => path.replace(/^\/api/, '/api'),
                },
            },
    },
    plugins: [
            laravel({
                input: ['resources/js/app.ts', 'resources/css/app.css'],
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
            },
        },
    },
});
