import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from './composables/useAppearance';
import { csrfHttp } from './api/http';

const appName = import.meta.env.VITE_APP_NAME || 'Tennis-App';

async function initializeCsrf() {
    try {
        await csrfHttp.get('/sanctum/csrf-cookie');
        console.log('Sanctum CSRF cookie fetched successfully');
    } catch (error) {
        console.error('Failed to fetch Sanctum CSRF cookie:', error);
    }
}

initializeCsrf().then(() => {
    createInertiaApp({
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) =>
            resolvePageComponent(
                `./pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./pages/**/*.vue')
            ),
        setup({ el, App, props, plugin }) {
            createApp({ render: () => h(App, props) })
                .use(plugin)
                .use(ZiggyVue)
                .mount(el);
        },
        progress: {
            color: '#4B5563',
        },
    });
    initializeTheme();
});
