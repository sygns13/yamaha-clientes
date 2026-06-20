/**
 * Composable para notificaciones toast.
 * Envuelve vue-toastification con métodos en español y configuración Yamaha.
 */
import { useToast as useVueToast } from 'vue-toastification';

export function useToast() {
    const toast = useVueToast();

    // Opciones base compartidas por todos los toasts
    const opcionesBase = {
        timeout:      4000,
        closeOnClick: true,
        pauseOnHover: true,
    };

    return {
        /** Notificación de éxito (verde) */
        exito: (mensaje) => toast.success(mensaje, opcionesBase),

        /** Notificación de error (rojo) */
        error: (mensaje) => toast.error(mensaje, { ...opcionesBase, timeout: 6000 }),

        /** Notificación informativa (azul) */
        info: (mensaje) => toast.info(mensaje, opcionesBase),

        /** Notificación de advertencia (amarillo) */
        advertencia: (mensaje) => toast.warning(mensaje, opcionesBase),
    };
}
