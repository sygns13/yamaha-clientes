<template>
    <div v-if="links.length > 3" class="flex items-center gap-4">
        <p class="text-sm text-gray-500">
            Mostrando <span class="font-semibold text-gray-700">{{ meta.from }}</span>–<span class="font-semibold text-gray-700">{{ meta.to }}</span>
            de <span class="font-semibold text-gray-700">{{ meta.total }}</span> registros
        </p>
        <div class="flex items-center gap-1">
            <template v-for="link in links" :key="link.label">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    :class="['px-3 py-1.5 rounded text-sm font-medium transition-colors',
                             link.active
                               ? 'bg-yamaha-blue text-white'
                               : 'bg-white text-gray-600 border border-gray-300 hover:bg-gray-50']"
                    v-html="traducirLabel(link.label)"
                />
                <span v-else
                    class="px-3 py-1.5 rounded text-sm text-gray-400 bg-gray-50 border border-gray-200"
                    v-html="traducirLabel(link.label)"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: { type: Array, default: () => [] },
    meta:  { type: Object, default: () => ({}) },
});

const traducirLabel = (label) => {
    return label
        .replace('pagination.previous', '&laquo; Anterior')
        .replace('pagination.next',     'Siguiente &raquo;');
};
</script>
