<template>
    <!--
        Botón reutilizable con variantes de color y tamaños.
        Props:
          variant: 'primary' | 'danger' | 'secondary' | 'ghost'
          size:    'sm' | 'md' | 'lg'
          loading: muestra spinner y bloquea clics
          type:    'button' | 'submit' | 'reset'
    -->
    <button
        :type="type"
        :disabled="disabled || loading"
        :class="[
            claseBase,
            claseVariante,
            claseTamano,
            (disabled || loading) ? 'opacity-60 cursor-not-allowed' : '',
        ]"
    >
        <!-- Spinner de carga -->
        <svg v-if="loading" class="animate-spin h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
        </svg>

        <!-- Contenido del botón (icono + texto) -->
        <slot/>
    </button>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant:  { type: String, default: 'primary' },   // Esquema de color
    size:     { type: String, default: 'md' },         // Tamaño
    type:     { type: String, default: 'button' },     // Tipo HTML
    loading:  { type: Boolean, default: false },        // Estado de carga
    disabled: { type: Boolean, default: false },        // Deshabilitado
});

// Clases comunes a todas las variantes
const claseBase = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-1';

// Colores por variante
const variantes = {
    primary:   'bg-yamaha-blue   hover:bg-yamaha-blue-dark  text-white  focus:ring-yamaha-blue',
    danger:    'bg-yamaha-red    hover:bg-yamaha-red-dark   text-white  focus:ring-yamaha-red',
    secondary: 'bg-gray-100      hover:bg-gray-200           text-gray-700 focus:ring-gray-300',
    ghost:     'bg-transparent   hover:bg-gray-100           text-gray-600 focus:ring-gray-300',
};

// Padding + tamaño de texto
const tamanos = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2   text-sm',
    lg: 'px-6 py-3   text-sm',
};

const claseVariante = computed(() => variantes[props.variant] ?? variantes.primary);
const claseTamano   = computed(() => tamanos[props.size]      ?? tamanos.md);
</script>
