<template>
    <Head title="BETA - VIN-1" />
    <div class="min-h-screen bg-moto flex items-center justify-center p-4">
        <div class="w-full max-w-lg">

            <!-- Card -->
            <div class="bg-white/90 rounded-2xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="bg-yamaha-blue px-8 py-5 border-b-4 border-yamaha-red">
                    <!-- Cambiamos el contenedor de la imagen -->
                    <div class="flex justify-center items-center mb-4">
                        <img
                            src="/img/logoyamaha-fullwhite.png"
                            alt="Mr. Lúpulo"
                            class="h-20 md:h-20 w-auto object-contain drop-shadow-lg scale-125 md:scale-100"
                            @error="onLogoError"
                        />
                    </div>
                    <h1 class="text-white text-xl font-estricta font-bold">Registro de Código VIN</h1>
                    <p class="text-blue-200 font-estricta text-md mt-1">Formulario 1 — Descripción del VIN</p>
                </div>

                <!-- Body -->
                <div class="px-8 py-4">

                    <!-- Éxito -->
                    <div v-if="showSuccess" class="text-center py-6">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-11 h-11 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2 font-estricta">¡Registro Exitoso!</h2>
                        <p class="text-gray-500 mb-7 font-estricta">Tu información ha sido guardada correctamente.</p>
                        <Link :href="route('vin1')"
                            class="inline-block bg-yamaha-blue hover:bg-yamaha-blue-dark text-white font-semibold px-7 py-3 rounded-lg transition-colors">
                            Registrar otro VIN
                        </Link>
                    </div>

                    <!-- Formulario -->
                    <form v-else @submit.prevent="submit" novalidate class="space-y-4">

                        <!-- Error global -->
                        <div v-if="Object.keys(form.errors).length || Object.keys(localErrors).length"
                            class="bg-red-50 border border-red-200 rounded-lg p-4 text-sm text-red-700 font-estricta">
                            Por favor corrige los errores indicados.
                        </div>

                        <!-- Nombres y Apellidos -->
                        <div>
                            <label class="block text-md font-semibold text-gray-700 mb-1 font-estricta">
                                Nombres y Apellidos <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.nombres_apellidos"
                                type="text"
                                placeholder="Ej: Juan Pérez García"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors font-estricta"
                                :class="{'border-red-400 bg-red-50': form.errors.nombres_apellidos || localErrors.nombres_apellidos}"
                            />
                            <p v-if="form.errors.nombres_apellidos || localErrors.nombres_apellidos" class="mt-1 text-xs text-red-600">
                                {{ form.errors.nombres_apellidos || localErrors.nombres_apellidos }}
                            </p>
                        </div>

                        <!-- Celular -->
                        <div>
                            <label class="block text-md font-semibold text-gray-700 mb-1 font-estricta">
                                Celular <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.celular"
                                type="tel"
                                maxlength="9"
                                placeholder="Ej: 987654321"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-sm
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50 font-estricta': form.errors.celular || localErrors.celular}"
                            />
                            <p v-if="form.errors.celular || localErrors.celular" class="mt-1 text-xs text-red-600">
                                {{ form.errors.celular || localErrors.celular }}
                            </p>
                        </div>

                        <!-- Correo -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Correo electrónico <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.correo"
                                type="email"
                                placeholder="correo@ejemplo.com"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.correo || localErrors.correo}"
                            />
                            <p v-if="form.errors.correo || localErrors.correo" class="mt-1 text-xs text-red-600">
                                {{ form.errors.correo || localErrors.correo }}
                            </p>
                        </div>

                        <!-- Modelo de Moto -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Modelo de Moto <span class="text-yamaha-red">*</span>
                            </label>
                            <select
                                v-model="form.modelo_moto_id"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.modelo_moto_id || localErrors.modelo_moto_id}"
                            >
                                <option value="">— Seleccione un modelo —</option>
                                <option v-for="m in modelos" :key="m.id" :value="m.id">
                                    {{ m.nombre }}
                                </option>
                                <option value="otros">OTROS</option>
                            </select>
                            <p v-if="form.errors.modelo_moto_id || localErrors.modelo_moto_id" class="mt-1 text-xs text-red-600">
                                {{ form.errors.modelo_moto_id || localErrors.modelo_moto_id }}
                            </p>
                            <!-- Input libre cuando elige OTROS -->
                            <div v-if="esOtroModelo" class="mt-2">
                                <input
                                    v-model="form.modelo_moto_otro"
                                    type="text"
                                    placeholder="Escribe el modelo de tu moto"
                                    maxlength="100"
                                    class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                           focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                           transition-colors"
                                    :class="{'border-red-400 bg-red-50': localErrors.modelo_moto_otro}"
                                />
                                <p v-if="localErrors.modelo_moto_otro" class="mt-1 text-xs text-red-600">
                                    {{ localErrors.modelo_moto_otro }}
                                </p>
                            </div>
                        </div>

                        <!-- Código VIN (texto) -->
                        <div>
                            <label class="block text-md font-estricta font-semibold text-gray-700 mb-1">
                                Código VIN <span class="text-yamaha-red">*</span>
                            </label>
                            <input
                                v-model="form.vin_descripcion"
                                type="text"
                                placeholder="Ej: LBVPC4267K2000001"
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta font-mono tracking-wide uppercase
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.vin_descripcion || localErrors.vin_descripcion}"
                            />
                            <p class="mt-1 text-sm text-gray-600 font-estricta">Escribe el código VIN tal como aparece en tu motocicleta.</p>
                            <p v-if="form.errors.vin_descripcion || localErrors.vin_descripcion" class="mt-1 text-xs text-red-600">
                                {{ form.errors.vin_descripcion || localErrors.vin_descripcion }}
                            </p>
                        </div>

                        <!-- Botón -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-yamaha-red hover:bg-yamaha-red-dark disabled:opacity-60 disabled:cursor-not-allowed
                                   text-white font-bold py-3 px-6 rounded-lg uppercase tracking-wider text-md
                                   transition-colors flex items-center justify-center gap-2 mt-2 font-estricta"
                        >
                            <svg v-if="form.processing" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                            </svg>
                            {{ form.processing ? 'Enviando...' : 'Registrar VIN' }}
                        </button>

                        <!-- Link a VIN2 -->
                        <p class="text-center text-sm text-gray-600 mt-3 font-estricta">
                            ¿Prefieres adjuntar una foto del VIN?
                            <Link :href="route('vin')" class="text-yamaha-blue hover:underline font-medium">
                                Usa el Formulario 2
                            </Link>
                        </p>

                    </form>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 border-t border-gray-100 px-8 py-3 flex items-center justify-between font-estricta">
                    <span class="text-sm text-gray-600">© {{ new Date().getFullYear() }} Yamaha Motor.</span>
                    <span class="text-sm font-semibold text-yamaha-red uppercase tracking-wider">VIN-1</span>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive } from 'vue';
import { useForm, usePage, Link, Head } from '@inertiajs/vue3';

defineProps({
    modelos: Array,
});

const page = usePage();
const showSuccess = computed(() => !!page.props.flash?.success);

const form = useForm({
    nombres_apellidos: '',
    celular:           '',
    correo:            '',
    modelo_moto_id:    '',
    modelo_moto_otro:  '',
    vin_descripcion:   '',
});

const esOtroModelo = computed(() => form.modelo_moto_id === 'otros');

const localErrors = reactive({});
function onLogoError(e) {
    e.target.style.display = 'none'
}
const validate = () => {
    Object.keys(localErrors).forEach(k => delete localErrors[k]);

    if (!form.nombres_apellidos.trim())
        localErrors.nombres_apellidos = 'El nombre es obligatorio.';
    else if (form.nombres_apellidos.trim().length < 3)
        localErrors.nombres_apellidos = 'Mínimo 3 caracteres.';

    if (!form.celular.trim())
        localErrors.celular = 'El celular es obligatorio.';
    else if (!/^[0-9+\s\-\(\)]{6,20}$/.test(form.celular.trim()))
        localErrors.celular = 'Formato de celular inválido.';

    if (!form.correo.trim())
        localErrors.correo = 'El correo es obligatorio.';
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.correo.trim()))
        localErrors.correo = 'Ingresa un correo electrónico válido.';

    if (!form.modelo_moto_id)
        localErrors.modelo_moto_id = 'Selecciona un modelo de moto.';
    else if (form.modelo_moto_id === 'otros' && !form.modelo_moto_otro.trim())
        localErrors.modelo_moto_otro = 'Escribe el modelo de tu moto.';

    if (!form.vin_descripcion.trim())
        localErrors.vin_descripcion = 'El código VIN es obligatorio.';
    else if (form.vin_descripcion.trim().length < 5)
        localErrors.vin_descripcion = 'El código VIN parece demasiado corto.';

    return Object.keys(localErrors).length === 0;
};

const submit = () => {
    if (!validate()) return;
    form.post(route('vin1.store'), { preserveScroll: true });
};
</script>

<style scoped>
input, select {
    @apply py-2.5 px-3.5;
}
</style>
