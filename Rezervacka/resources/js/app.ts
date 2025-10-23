import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme, updateTheme } from './composables/useAppearance';
import vue3StarRatings from "vue3-star-ratings";

// FontAwesome imports
import { library } from '@fortawesome/fontawesome-svg-core';
import { faArrowUp } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

// Pridaj ikonu do knižnice
library.add(faArrowUp);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';


createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) }).use(plugin);

        // Zaregistruj FontAwesome komponentu globálne
        app.component('FontAwesomeIcon', FontAwesomeIcon);
        app.component("vue3-star-ratings", vue3StarRatings);
        app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...

initializeTheme();
updateTheme("light")
