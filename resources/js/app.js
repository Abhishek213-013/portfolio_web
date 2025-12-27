import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

// Import Font Awesome
import '@fortawesome/fontawesome-free/css/all.css';

// Import animation library CSS
import 'aos/dist/aos.css';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'glightbox/dist/css/glightbox.css';

const appName = import.meta.env.VITE_APP_NAME || 'Portfolio Admin';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy) // ✅ Pass Ziggy as second parameter
            .mount(el);
    },
    progress: {
        color: '#4f46e5',
    },
});