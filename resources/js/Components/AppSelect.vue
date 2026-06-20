<template>
    <!--
        Select reutilizable con etiqueta y manejo de errores.
        Slot por defecto para las opciones:
          <AppSelect v-model="form.role" label="Rol">
              <option value="">Seleccione...</option>
              <option value="admin">Administrador</option>
          </AppSelect>
    -->
    <div>
        <!-- Etiqueta -->
        <label v-if="label" class="block text-sm font-semibold text-gray-700 mb-1">
            {{ label }}
            <span v-if="required" class="text-yamaha-red ml-0.5">*</span>
        </label>

        <!-- Select nativo -->
        <select
            :value="modelValue"
            :class="[
                'w-full rounded-lg border shadow-sm text-sm py-2.5 px-3.5 transition-colors',
                'focus:outline-none focus:ring-1',
                error
                    ? 'border-red-400 bg-red-50 focus:ring-red-400 focus:border-red-400'
                    : 'border-gray-300 focus:ring-yamaha-blue focus:border-yamaha-blue',
            ]"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <slot/>
        </select>

        <!-- Mensaje de error -->
        <p v-if="error" class="mt-1 text-xs text-red-600">{{ error }}</p>
    </div>
</template>

<script setup>
defineProps({
    modelValue: { type: [String, Number, Boolean], default: '' },
    label:      { type: String, default: '' },
    error:      { type: String, default: '' },
    required:   { type: Boolean, default: false },
});

defineEmits(['update:modelValue']);
</script>
