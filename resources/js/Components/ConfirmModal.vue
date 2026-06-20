<template>
    <!--
        Modal de confirmación global.
        Controlado por el composable useConfirm.
        Debe incluirse una sola vez en AdminLayout.vue.
    -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-150 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="estado.visible"
                class="fixed inset-0 z-[60] flex items-center justify-center p-4"
                @click.self="cancelar"
            >
                <!-- Fondo oscuro -->
                <div class="absolute inset-0 bg-black/60"/>

                <!-- Tarjeta del modal -->
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">

                    <!-- Cuerpo -->
                    <div class="px-6 py-5">
                        <div class="flex items-start gap-4">
                            <!-- Icono de advertencia -->
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <!-- Texto -->
                            <div>
                                <h3 class="text-base font-semibold text-gray-900 mb-1">{{ estado.titulo }}</h3>
                                <p class="text-sm text-gray-600 leading-relaxed">{{ estado.mensaje }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex gap-3 px-6 pb-5">
                        <button
                            @click="cancelar"
                            class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="aceptar"
                            class="flex-1 py-2.5 bg-yamaha-red hover:bg-yamaha-red-dark text-white rounded-lg text-sm font-semibold transition-colors"
                        >
                            Confirmar
                        </button>
                    </div>

                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { useConfirm } from '@/Composables/useConfirm';

// El composable expone el estado reactivo compartido
const { estado, aceptar, cancelar } = useConfirm();
</script>
