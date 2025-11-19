import './bootstrap';
import '../css/app.css';
import '../assets/styles.scss';
import "tailwindcss-primeui";

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy';
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import {definePreset} from '@primeuix/themes';
import UserCanPlugin from "@/plugins/userCan.js";


const appName = 'OOO Intranet';

const MainPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: '{indigo.50}',
            100: '{indigo.100}',
            200: '{indigo.200}',
            300: '{indigo.300}',
            400: '{indigo.400}',
            500: '{indigo.500}',
            600: '{indigo.600}',
            700: '{indigo.700}',
            800: '{indigo.800}',
            900: '{indigo.900}',
            950: '{indigo.950}'
        }
    }
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)})
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue,
                {
                    theme: {
                        preset: MainPreset,
                        options: {
                            darkModeSelector: '.app-dark'
                        },
                        primary: 'indigo'
                    }
                })
            .use(ToastService)
            .use(ConfirmationService)
            .use(UserCanPlugin);

        app.config.globalProperties.$formatCurrency = (value) => {
            return new Intl.NumberFormat('cs-CZ', {
                style: 'currency',
                currency: 'CZK'
            }).format(value);
        };

        app.config.globalProperties.$formatDateTime = (value) => {
            return new Intl.DateTimeFormat('cs-CZ', {dateStyle: 'medium', timeStyle: 'medium'}).format(new Date(value));
        };

        return app.mount(el);
    },
    progress: {
        color: '#005de0',
    },
});
