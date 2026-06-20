/**
 * Composable para diálogos de confirmación.
 * Expone un estado reactivo compartido con el componente ConfirmModal.vue.
 * Uso: const { confirmar } = useConfirm()
 *      const ok = await confirmar('¿Eliminar este registro?')
 *      if (ok) { ... }
 */
import { ref } from 'vue';

// Estado singleton — compartido entre todas las instancias del composable
const estado = ref({
    visible:  false,
    mensaje:  '',
    titulo:   'Confirmar acción',
    resolve:  null,
});

export function useConfirm() {
    /**
     * Muestra el modal de confirmación y retorna una promesa.
     * @param {string} mensaje - Texto de la pregunta
     * @param {string} [titulo] - Título opcional del modal
     * @returns {Promise<boolean>} true si el usuario confirmó, false si canceló
     */
    const confirmar = (mensaje, titulo = 'Confirmar acción') => {
        estado.value.mensaje = mensaje;
        estado.value.titulo  = titulo;
        estado.value.visible = true;

        return new Promise((resolve) => {
            estado.value.resolve = resolve;
        });
    };

    /** Llamado desde ConfirmModal cuando el usuario acepta */
    const aceptar = () => {
        estado.value.visible = false;
        estado.value.resolve?.(true);
    };

    /** Llamado desde ConfirmModal cuando el usuario cancela */
    const cancelar = () => {
        estado.value.visible = false;
        estado.value.resolve?.(false);
    };

    return { estado, confirmar, aceptar, cancelar };
}
