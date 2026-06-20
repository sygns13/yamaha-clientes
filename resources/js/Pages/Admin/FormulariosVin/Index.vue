<template>
    <Head title="Formularios VIN" />
    <AdminLayout title="Formularios VIN">

        <!-- ── Barra de filtros ───────────────────────────────────────────── -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-4">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">

                <!-- Filtro por tipo de formulario -->
                <select v-model="filtros.tipo"
                    class="rounded-lg border-gray-300 text-sm focus:ring-1 focus:ring-yamaha-blue focus:border-yamaha-blue">
                    <option value="">Todos los tipos</option>
                    <option value="VIN1">VIN-1 (Texto)</option>
                    <option value="VIN2">VIN-2 (Foto)</option>
                </select>

                <!-- Búsqueda por nombre del cliente -->
                <input v-model="filtros.nombres" type="text" placeholder="Buscar por nombre…"
                    class="rounded-lg border-gray-300 text-sm focus:ring-1 focus:ring-yamaha-blue focus:border-yamaha-blue"/>

                <!-- Búsqueda por celular -->
                <input v-model="filtros.celular" type="text" placeholder="Buscar por celular…"
                    class="rounded-lg border-gray-300 text-sm focus:ring-1 focus:ring-yamaha-blue focus:border-yamaha-blue"/>

                <!-- Filtro por modelo de moto -->
                <select v-model="filtros.modelo"
                    class="rounded-lg border-gray-300 text-sm focus:ring-1 focus:ring-yamaha-blue focus:border-yamaha-blue">
                    <option value="">Todos los modelos</option>
                    <option v-for="m in modelos" :key="m.id" :value="m.id">{{ m.nombre }}</option>
                    <option value="otros">OTROS (texto libre)</option>
                </select>

                <!-- Búsqueda por código VIN -->
                <input v-model="filtros.vin" type="text" placeholder="Buscar código VIN…"
                    class="rounded-lg border-gray-300 text-sm focus:ring-1 focus:ring-yamaha-blue focus:border-yamaha-blue"/>

                <!-- Acciones: Limpiar filtros + Exportar Excel -->
                <div class="flex gap-2">
                    <AppButton variant="secondary" class="flex-1" @click="limpiarFiltros">
                        Limpiar
                    </AppButton>
                    <a :href="urlExportar"
                        class="flex-1 px-3 py-2 bg-yamaha-blue hover:bg-yamaha-blue-dark text-white rounded-lg text-sm font-medium transition-colors text-center flex items-center justify-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel
                    </a>
                </div>
            </div>
        </div>

        <!-- ── Tabla de registros ─────────────────────────────────────────── -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-yamaha-blue text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">#</th>
                            <th class="px-4 py-3 text-left font-semibold">Cliente</th>
                            <th class="px-4 py-3 text-left font-semibold">Celular</th>
                            <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Correo</th>
                            <th class="px-4 py-3 text-left font-semibold">Modelo</th>
                            <th class="px-4 py-3 text-left font-semibold">Tipo</th>
                            <th class="px-4 py-3 text-left font-semibold">Código VIN</th>
                            <th class="px-4 py-3 text-left font-semibold hidden lg:table-cell">Fecha</th>
                            <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                        <!-- Mensaje cuando no hay resultados -->
                        <tr v-if="!registros.data.length">
                            <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                                No se encontraron registros con los filtros aplicados.
                            </td>
                        </tr>

                        <tr v-for="(r, i) in registros.data" :key="r.id" class="hover:bg-gray-50 transition-colors">

                            <!-- Número correlativo de fila -->
                            <td class="px-4 py-3 text-gray-400 font-mono text-xs">
                                {{ (registros.meta.current_page - 1) * registros.meta.per_page + i + 1 }}
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-900 max-w-[140px] truncate">
                                {{ r.cliente.nombres_apellidos }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">{{ r.cliente.celular }}</td>

                            <td class="px-4 py-3 text-gray-600 hidden md:table-cell max-w-[160px] truncate">
                                {{ r.cliente.correo || '—' }}
                            </td>

                            <td class="px-4 py-3 text-gray-600">{{ r.modelo_moto.nombre }}</td>

                            <!-- Badge del tipo de formulario -->
                            <td class="px-4 py-3">
                                <AppBadge :variant="r.tipo_formulario === 'VIN1' ? 'blue' : 'red'">
                                    {{ r.tipo_formulario }}
                                </AppBadge>
                            </td>

                            <!-- VIN-1: código de texto | VIN-2: miniatura clickeable -->
                            <td class="px-4 py-3">
                                <span v-if="r.tipo_formulario === 'VIN1'"
                                    class="font-mono text-xs text-gray-700 uppercase">
                                    {{ r.vin_descripcion }}
                                </span>
                                <button v-else-if="r.vin_imagen_url"
                                    @click="imagenModal = r.vin_imagen_url"
                                    class="block hover:opacity-80 transition-opacity">
                                    <img :src="r.vin_imagen_url" alt="VIN foto"
                                        class="h-10 w-16 object-cover rounded border border-gray-200 cursor-zoom-in"/>
                                </button>
                                <span v-else class="text-gray-400 text-xs">Sin imagen</span>
                            </td>

                            <td class="px-4 py-3 text-gray-500 text-xs hidden lg:table-cell whitespace-nowrap">
                                {{ r.created_at }}
                            </td>

                            <!-- Acciones: Ver imagen (VIN2) + Eliminar -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">

                                    <!-- Ver imagen (solo VIN2 con imagen) -->
                                    <button v-if="r.tipo_formulario === 'VIN2' && r.vin_imagen_url"
                                        @click="imagenModal = r.vin_imagen_url"
                                        title="Ver imagen"
                                        class="p-1.5 text-yamaha-blue hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    <!-- Eliminar registro -->
                                    <button @click="eliminar(r)" title="Eliminar"
                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer: filas por página + paginación -->
            <div class="px-4 pb-4">
                <div class="flex flex-wrap items-center justify-between gap-3 mt-3">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <label>Filas por página:</label>
                        <select v-model="perPage"
                            class="rounded-lg border-gray-300 text-sm py-1 focus:ring-1 focus:ring-yamaha-blue focus:border-yamaha-blue">
                            <option v-for="n in [10, 15, 25, 50, 100]" :key="n" :value="n">{{ n }}</option>
                        </select>
                    </div>
                    <Pagination :links="registros.meta.links" :meta="registros.meta"/>
                </div>
            </div>
        </div>

        <!-- ── Modal de imagen VIN-2 ──────────────────────────────────────── -->
        <Teleport to="body">
            <div v-if="imagenModal"
                class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center p-4"
                @click.self="imagenModal = null">
                <div class="relative bg-white rounded-xl overflow-hidden max-w-2xl w-full shadow-2xl">

                    <!-- Encabezado del modal de imagen -->
                    <div class="flex items-center justify-between px-4 py-3 border-b">
                        <h3 class="font-semibold text-gray-700 text-sm">Foto del Código VIN</h3>
                        <button @click="imagenModal = null" class="text-gray-400 hover:text-gray-600 p-1 rounded">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Imagen a pantalla completa -->
                    <div class="p-4 bg-gray-50">
                        <img :src="imagenModal" alt="Código VIN"
                            class="max-h-[70vh] mx-auto object-contain rounded"/>
                    </div>

                    <!-- Pie del modal con opciones -->
                    <div class="flex justify-end px-4 py-3 border-t gap-2">
                        <a :href="imagenModal" target="_blank">
                            <AppButton variant="primary">Abrir en pestaña</AppButton>
                        </a>
                        <AppButton variant="secondary" @click="imagenModal = null">Cerrar</AppButton>
                    </div>
                </div>
            </div>
        </Teleport>

    </AdminLayout>
</template>

<script setup>
import { ref, reactive, watch, computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout    from '@/Layouts/AdminLayout.vue';
import Pagination     from '@/Components/Pagination.vue';
import AppButton      from '@/Components/AppButton.vue';
import AppBadge       from '@/Components/AppBadge.vue';
import { useConfirm } from '@/Composables/useConfirm';

// Props recibidos desde FormulariosVinController
const props = defineProps({
    registros: Object,  // Colección paginada de motocicletas
    modelos:   Array,   // Lista de modelos para el filtro
    filtros:   Object,  // Filtros activos (preservados entre páginas)
    perPage:   Number,  // Filas por página actualmente seleccionadas
});

// Composable para confirmaciones sin window.confirm()
const { confirmar } = useConfirm();

// URL de la imagen actualmente mostrada en el modal
const imagenModal = ref(null);

// Estado reactivo de los filtros (inicializado con los valores del servidor)
const filtros = reactive({
    tipo:    props.filtros.tipo    || '',
    nombres: props.filtros.nombres || '',
    celular: props.filtros.celular || '',
    modelo:  props.filtros.modelo  || '',
    vin:     props.filtros.vin     || '',
});

// Filas por página (preferencia de UI, no se resetea al limpiar filtros)
const perPage = ref(props.perPage || 15);

/** Construye los params de navegación combinando filtros activos + per_page */
const buildParams = () => {
    const params = Object.fromEntries(
        Object.entries(filtros).filter(([, v]) => v !== '')
    );
    params.per_page = perPage.value;
    return params;
};

/** Navega al servidor con los filtros y per_page actuales */
const navegar = () => {
    router.get(route('admin.formularios-vin.index'), buildParams(), {
        preserveState: true,
        replace:       true,
    });
};

// Watcher de filtros con debounce de 400ms (entradas de texto)
let debounceTimer;
watch(filtros, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(navegar, 400);
});

// Watcher de filas por página sin debounce (acción explícita del usuario)
watch(perPage, navegar);

/** Limpia todos los filtros de datos (perPage se conserva intencionalmente) */
const limpiarFiltros = () => {
    Object.keys(filtros).forEach(k => { filtros[k] = ''; });
};

/**
 * URL de exportación a Excel — incluye filtros pero NO per_page
 * (el Excel exporta todos los registros coincidentes, no solo la página actual).
 */
const urlExportar = computed(() => {
    const params = new URLSearchParams(
        Object.fromEntries(Object.entries(filtros).filter(([, v]) => v !== ''))
    );
    return route('admin.formularios-vin.export-excel') + (params.toString() ? '?' + params : '');
});

/** Elimina un registro con confirmación previa mediante ConfirmModal */
const eliminar = async (r) => {
    const nombre = r.cliente.nombres_apellidos;
    const ok = await confirmar(
        `¿Eliminar el registro de "${nombre}" (${r.tipo_formulario})? Esta acción no se puede deshacer.`,
        'Eliminar registro'
    );
    if (!ok) return;

    router.delete(route('admin.formularios-vin.destroy', r.id), { preserveScroll: true });
};
</script>
