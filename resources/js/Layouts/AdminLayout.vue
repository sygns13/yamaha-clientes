<template>
    <!--
        Layout principal del panel de administración.
        Incluye: sidebar con navegación, topbar, área de contenido.
        El modal de confirmación (ConfirmModal) se monta globalmente aquí.
    -->
    <div class="flex h-screen bg-gray-100 overflow-hidden">

        <!-- Overlay para cerrar el sidebar en móvil -->
        <div v-if="sidebarOpen"
            class="fixed inset-0 z-30 bg-black/50 md:hidden"
            @click="sidebarOpen = false"
        />

        <!-- ── Sidebar ────────────────────────────────────────────────────── -->
        <aside :class="[
            'fixed md:static inset-y-0 left-0 z-40 w-64 bg-yamaha-blue-dark flex flex-col transition-transform duration-200',
            sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0',
        ]">

            <!-- Logo de la marca -->
            <div class="flex items-center gap-3 px-5 py-5 border-b border-yamaha-blue flex-shrink-0">
                <div class="flex-1 flex justify-center">
                    <img
                        src="/img/logoyamaha-fullwhite.png"
                        alt="Yamaha"
                        class="h-12 lg:h-16 w-auto object-contain drop-shadow-lg scale-125 md:scale-100"
                        @error="onLogoError"
                    />
                </div>
            </div>

            <!-- Navegación principal -->
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <p class="text-blue-400 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2">
                    Menú principal
                </p>

                <!-- Dashboard — visible para todos los usuarios autenticados -->
                <Link :href="route('dashboard')" :class="navClass(isActive('/dashboard'))">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </Link>

                <!-- Formularios VIN — visible para admin y trabajador -->
                <Link :href="route('admin.formularios-vin.index')" :class="navClass(isActive('/admin/formularios-vin'))">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Formularios VIN
                </Link>

                <!-- Gestión de Usuarios — solo para administradores -->
                <Link v-if="esAdmin" :href="route('admin.usuarios.index')" :class="navClass(isActive('/admin/usuarios'))">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Gestión de Usuarios
                </Link>

                <div class="border-t border-yamaha-blue my-3"/>

                <!-- Accesos directos a los formularios públicos -->
                <p class="text-blue-400 text-[10px] font-semibold uppercase tracking-widest px-3 mb-2">
                    Registros públicos
                </p>

                <a :href="route('vin1')" target="_blank" :class="navClass(false)">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                    </svg>
                    Formulario VIN-1
                </a>

                <a :href="route('vin')" target="_blank" :class="navClass(false)">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Formulario VIN-2
                </a>
            </nav>

            <!-- Información y cierre de sesión del usuario -->
            <div class="border-t border-yamaha-blue px-5 py-4 flex-shrink-0">
                <p class="text-white text-sm font-semibold truncate">{{ page.props.auth.user.name }}</p>
                <p class="text-blue-300 text-xs truncate mb-2">{{ page.props.auth.user.email }}</p>
                <Link :href="route('logout')" method="post" as="button"
                    class="text-xs text-blue-300 hover:text-yamaha-red transition-colors flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </Link>
            </div>
        </aside>

        <!-- ── Área principal ─────────────────────────────────────────────── -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Topbar con título y rol del usuario -->
            <header class="bg-white shadow-sm z-10 flex items-center gap-4 px-6 h-16 flex-shrink-0 border-b-2 border-yamaha-red">
                <!-- Botón hamburguesa — solo visible en móvil -->
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700 p-1">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Título de la sección actual -->
                <h1 class="text-lg font-semibold text-gray-800 flex-1">{{ title }}</h1>

                <!-- Badge de rol del usuario (Admin = rojo, Trabajador = azul) -->
                <AppBadge :variant="esAdmin ? 'red' : 'blue'" size="sm">
                    {{ page.props.auth.user.role }}
                </AppBadge>
            </header>

            <!-- Contenido de la página actual -->
            <main class="flex-1 overflow-y-auto p-6">
                <slot/>
            </main>
        </div>

        <!-- Modal de confirmación global (requiere useConfirm en las páginas) -->
        <ConfirmModal/>

    </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { useToast } from '@/Composables/useToast';
import AppBadge     from '@/Components/AppBadge.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

defineProps({
    title: { type: String, default: 'Dashboard' },
});

const page        = usePage();
const sidebarOpen = ref(false);

// Composable de notificaciones toast
const { exito, error: mostrarError } = useToast();

// Determinar si el usuario actual es administrador
const esAdmin = computed(() => page.props.auth.user?.role === 'admin');

/**
 * Observar cambios en los mensajes flash del servidor y mostrarlos como toasts.
 * Esto reemplaza los divs de alerta manuales en el layout.
 */
watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) exito(flash.success);
        if (flash?.error)   mostrarError(flash.error);
    },
    { deep: true }
);

/**
 * Determina si una ruta está activa comparando el inicio de la URL actual.
 * @param {string} path - Ruta a comparar (ej: '/dashboard')
 */
const isActive = (path) => page.url.startsWith(path);

/**
 * Clases Tailwind para los ítems de navegación del sidebar.
 * @param {boolean} activo - Si la ruta está activa
 */
const navClass = (activo) => [
    'flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors',
    activo
        ? 'bg-yamaha-blue text-white'
        : 'text-blue-200 hover:bg-yamaha-blue hover:text-white',
].join(' ');
</script>
