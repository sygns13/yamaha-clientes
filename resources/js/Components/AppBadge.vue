<template>
    <!--
        Badge (etiqueta de color) reutilizable.
        Props:
          variant: 'blue' | 'red' | 'green' | 'gray' | 'orange' | 'indigo'
          size:    'sm' | 'xs'
    -->
    <span :class="['inline-flex items-center rounded-full font-bold uppercase tracking-wide', claseVariante, claseTamano]">
        <slot/>
    </span>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: { type: String, default: 'blue' },  // Esquema de color
    size:    { type: String, default: 'xs' },     // Tamaño del badge
});

// Colores disponibles — incluye los colores de marca Yamaha
const variantes = {
    blue:   'bg-yamaha-blue  text-white',
    red:    'bg-yamaha-red   text-white',
    green:  'bg-green-100    text-green-700',
    gray:   'bg-gray-100     text-gray-500',
    orange: 'bg-orange-100   text-orange-700',
    indigo: 'bg-indigo-100   text-indigo-700',
};

const tamanos = {
    xs: 'px-2 py-0.5 text-[10px]',
    sm: 'px-2.5 py-1 text-xs',
};

const claseVariante = computed(() => variantes[props.variant] ?? variantes.blue);
const claseTamano   = computed(() => tamanos[props.size]      ?? tamanos.xs);
</script>
