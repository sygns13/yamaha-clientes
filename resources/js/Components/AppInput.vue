<template>
    <!--
        Input reutilizable con etiqueta, mensaje de error y texto de ayuda.
        Soporta v-model y todos los atributos nativos de <input> via $attrs.
        Props:
          label:    Texto de la etiqueta (opcional)
          error:    Mensaje de error (string)
          hint:     Texto de ayuda (se oculta si hay error)
          required: Muestra asterisco rojo en la etiqueta
    -->
    <div>
        <!-- Etiqueta -->
        <label v-if="label" class="block text-sm font-semibold text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-yamaha-red ml-0.5">*</span>
        </label>

        <!-- Campo de entrada -->
        <input
            v-bind="$attrs"
            :value="modelValue"
            :class="[
                'w-full rounded-lg border shadow-sm text-sm py-2.5 px-3.5 transition-colors',
                'focus:outline-none focus:ring-1',
                error
                    ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400'
                    : 'border-gray-300 focus:ring-yamaha-blue focus:border-yamaha-blue',
            ]"
            @input="$emit('update:modelValue', $event.target.value)"
        />

        <!-- Texto de ayuda (solo si no hay error) -->
        <p v-if="hint && !error" class="mt-1 text-xs text-gray-400">{{ hint }}</p>

        <!-- Mensaje de error -->
        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
// Evitar que $attrs se hereden automáticamente en el div raíz
defineOptions({ inheritAttrs: false });

defineProps({
    modelValue: { type: [String, Number], default: '' },
    label:      { type: String, default: '' },
    error:      { type: String, default: '' },
    hint:       { type: String, default: '' },
    required:   { type: Boolean, default: false },
});

defineEmits(['update:modelValue']);
</script>
