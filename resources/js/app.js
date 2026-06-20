import '../css/app.css';
import './bootstrap';

// Estilos de vue-toastification
import 'vue-toastification/dist/index.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Toast from 'vue-toastification';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Opciones globales del sistema de notificaciones
const opcionesToast = {
    position:         'top-right',
    timeout:          4000,
    closeOnClick:     true,
    pauseOnHover:     true,
    draggable:        true,
    maxToasts:        5,
    newestOnTop:      true,
};

createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            // Registrar vue-toastification globalmente
            .use(Toast, opcionesToast)
            .mount(el);
    },

    // Barra de progreso con el rojo Yamaha
    progress: {
        color: '#E60012',
    },
});
