import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// AdminLTE 4 usa Bootstrap 5, no necesita jQuery obligatoriamente
// Pero si AdminLTE 4 aún lo requiere, lo incluimos
import $ from 'jquery';
window.$ = window.jQuery = $;

// EVENT
import './echo'; // Importa la configuración de Echo
// Bootstrap 5 CSS
import 'bootstrap/dist/css/bootstrap.min.css';

// Después de Bootstrap 5 CSS
import 'bootstrap-icons/font/bootstrap-icons.css';
// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// AdminLTE 4 CSS
import 'admin-lte/dist/css/adminlte.min.css';
import '../css/theme.css';
import '../css/accessibility.css';
// Bootstrap 5 JS (incluye Popper)
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// AdminLTE 4 JS
import 'admin-lte/dist/js/adminlte.min.js';

// Configurar Ziggy (si lo tienes instalado)
let ZiggyVue;
try {
    ZiggyVue = (await import('../../vendor/tightenco/ziggy')).ZiggyVue;
} catch (e) {
    console.log('Ziggy no está instalado');
}

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin);

        if (ZiggyVue) {
            app.use(ZiggyVue);
        }

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});