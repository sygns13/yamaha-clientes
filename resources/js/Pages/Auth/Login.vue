<template>
    <Head title="Iniciar Sesión" />
    <div class="min-h-screen bg-moto-two flex items-center justify-center p-4">
        <div class="w-full max-w-md">

            <div class="bg-white/90 rounded-2xl shadow-2xl overflow-hidden">

                <!-- Header -->
                <div class="bg-yamaha-blue px-8 py-6 border-b-4 border-yamaha-red">
                    <div class="flex justify-center items-center mb-4">
                        <img
                            src="/img/logoyamaha-fullwhite.png"
                            alt="Yamaha"
                            class="h-20 w-auto object-contain drop-shadow-lg scale-125 md:scale-100"
                            @error="onLogoError"
                        />
                    </div>
                    <h1 class="text-white text-2xl font-estricta font-bold">Panel Administrativo</h1>
                    <p class="text-blue-200 font-estricta text-md mt-1">Ingresa tus credenciales para continuar</p>
                </div>

                <!-- Body -->
                <div class="px-8 py-7">

                    <!-- Mensaje de estado (ej: enlace de restablecimiento enviado) -->
                    <div v-if="status"
                        class="mb-4 rounded-lg bg-green-50 border border-green-200 p-4 text-sm text-green-700 font-estricta">
                        {{ status }}
                    </div>

                    <!-- Error global (cuenta inactiva, credenciales inválidas, etc.) -->
                    <div v-if="flashError && !form.errors.email"
                        class="mb-4 rounded-lg bg-red-50 border border-red-200 p-4 text-sm text-red-700 font-estricta">
                        {{ flashError }}
                    </div>

                    <form @submit.prevent="submit" novalidate class="space-y-4">

                        <!-- Correo electrónico -->
                        <div>
                            <label class="block text-md font-semibold text-gray-700 mb-1 font-estricta">
                                Correo electrónico
                            </label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="admin@yamaha.com"
                                autocomplete="username"
                                autofocus
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.email}"
                            />
                            <p v-if="form.errors.email" class="mt-1 text-xs text-red-600 font-estricta">
                                {{ form.errors.email }}
                            </p>
                        </div>

                        <!-- Contraseña -->
                        <div>
                            <label class="block text-md font-semibold text-gray-700 mb-1 font-estricta">
                                Contraseña
                            </label>
                            <input
                                v-model="form.password"
                                type="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm text-md font-estricta
                                       focus:border-yamaha-blue focus:ring-1 focus:ring-yamaha-blue
                                       transition-colors"
                                :class="{'border-red-400 bg-red-50': form.errors.password}"
                            />
                            <p v-if="form.errors.password" class="mt-1 text-xs text-red-600 font-estricta">
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Recordarme + ¿Olvidaste tu contraseña? -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input
                                    type="checkbox"
                                    v-model="form.remember"
                                    class="rounded border-gray-300 text-yamaha-blue focus:ring-yamaha-blue"
                                />
                                <span class="text-sm text-gray-600 font-estricta">Recordarme</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm text-yamaha-blue hover:underline font-estricta"
                            >
                                ¿Olvidaste tu contraseña?
                            </Link>
                        </div>

                        <!-- Botón ingresar -->
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
                            {{ form.processing ? 'Verificando...' : 'Ingresar al Panel' }}
                        </button>

                    </form>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 border-t border-gray-100 px-8 py-3 flex items-center justify-between font-estricta">
                    <span class="text-sm text-gray-600">© {{ new Date().getFullYear() }} Yamaha Motor.</span>
                    <span class="text-sm font-semibold text-yamaha-red uppercase tracking-wider">ADMIN</span>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const page = usePage();
const flashError = computed(() => page.props.flash?.error || page.props.errors?.email || null);

const form = useForm({
    email:    '',
    password: '',
    remember: false,
});

function onLogoError(e) {
    e.target.style.display = 'none';
}

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<style scoped>
input[type="email"],
input[type="password"] {
    @apply py-2.5 px-3.5;
}
</style>
