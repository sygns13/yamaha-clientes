<template>
    <Head title="Gestión de Usuarios" />
    <AdminLayout title="Gestión de Usuarios">

        <!-- ── Cabecera ───────────────────────────────────────────────────── -->
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500">{{ usuarios.meta.total }} usuario(s) registrado(s)</p>
            <AppButton variant="primary" @click="abrirCrear">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Usuario
            </AppButton>
        </div>

        <!-- ── Tabla de usuarios ──────────────────────────────────────────── -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-yamaha-blue text-white">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                            <th class="px-4 py-3 text-left font-semibold">Correo</th>
                            <th class="px-4 py-3 text-left font-semibold">Rol</th>
                            <th class="px-4 py-3 text-left font-semibold">Estado</th>
                            <th class="px-4 py-3 text-left font-semibold hidden md:table-cell">Creado</th>
                            <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-for="u in usuarios.data" :key="u.id" class="hover:bg-gray-50 transition-colors">

                            <!-- Nombre + badge "Tú" para el usuario actual -->
                            <td class="px-4 py-3">
                                <span class="font-medium text-gray-900">{{ u.name }}</span>
                                <AppBadge v-if="u.is_self" variant="gray" class="ml-2">Tú</AppBadge>
                            </td>

                            <td class="px-4 py-3 text-gray-600">{{ u.email }}</td>

                            <!-- Badge de rol con color diferenciado -->
                            <td class="px-4 py-3">
                                <AppBadge :variant="u.role === 'admin' ? 'red' : 'blue'">
                                    {{ u.role === 'admin' ? 'Admin' : 'Trabajador' }}
                                </AppBadge>
                            </td>

                            <!-- Badge de estado activo/inactivo -->
                            <td class="px-4 py-3">
                                <AppBadge :variant="u.activo ? 'green' : 'gray'">
                                    {{ u.activo ? 'Activo' : 'Inactivo' }}
                                </AppBadge>
                            </td>

                            <td class="px-4 py-3 text-gray-500 text-xs hidden md:table-cell">{{ u.created_at }}</td>

                            <!-- Acciones: Editar / Toggle activo / Eliminar -->
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1.5">

                                    <!-- Editar usuario -->
                                    <button @click="abrirEditar(u)" title="Editar"
                                        class="p-1.5 text-yamaha-blue hover:bg-blue-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Activar / Desactivar (no aplica al propio usuario) -->
                                    <button v-if="!u.is_self" @click="cambiarEstado(u)"
                                        :title="u.activo ? 'Desactivar' : 'Activar'"
                                        :class="['p-1.5 rounded-lg transition-colors',
                                                 u.activo ? 'text-orange-500 hover:bg-orange-50' : 'text-green-600 hover:bg-green-50']">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path v-if="u.activo" stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            <path v-else stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </button>

                                    <!-- Eliminar (no aplica al propio usuario) -->
                                    <button v-if="!u.is_self" @click="eliminar(u)"
                                        title="Eliminar"
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

            <!-- Paginación -->
            <div class="px-4 pb-4">
                <Pagination :links="usuarios.links" :meta="usuarios.meta"/>
            </div>
        </div>

        <!-- ── Modal Crear / Editar usuario ──────────────────────────────── -->
        <Teleport to="body">
            <div v-if="modal.show"
                class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4"
                @click.self="cerrarModal">

                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

                    <!-- Encabezado del modal con colores Yamaha -->
                    <div class="bg-yamaha-blue px-6 py-4 border-b-4 border-yamaha-red flex items-center justify-between">
                        <h2 class="text-white font-bold text-lg">
                            {{ modal.modo === 'crear' ? 'Nuevo Usuario' : 'Editar Usuario' }}
                        </h2>
                        <button @click="cerrarModal" class="text-blue-200 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Formulario del modal -->
                    <form @submit.prevent="guardar" class="p-6 space-y-4">

                        <!-- Campo Nombre -->
                        <AppInput
                            v-model="form.name"
                            label="Nombre"
                            placeholder="Nombre completo"
                            :error="form.errors.name"
                            required
                        />

                        <!-- Campo Correo -->
                        <AppInput
                            v-model="form.email"
                            type="email"
                            label="Correo"
                            placeholder="correo@ejemplo.com"
                            :error="form.errors.email"
                            required
                        />

                        <!-- Campo Contraseña -->
                        <AppInput
                            v-model="form.password"
                            type="password"
                            label="Contraseña"
                            placeholder="Mínimo 8 caracteres"
                            :error="form.errors.password"
                            :required="modal.modo === 'crear'"
                            :hint="modal.modo === 'editar' ? 'Dejar en blanco para no cambiar' : ''"
                        />

                        <!-- Campo Confirmar Contraseña -->
                        <AppInput
                            v-model="form.password_confirmation"
                            type="password"
                            label="Confirmar Contraseña"
                            placeholder="Repite la contraseña"
                        />

                        <!-- Fila Rol + Estado -->
                        <div class="grid grid-cols-2 gap-3">
                            <AppSelect v-model="form.role" label="Rol" :error="form.errors.role" required>
                                <option value="trabajador">Trabajador</option>
                                <option value="admin">Administrador</option>
                            </AppSelect>

                            <AppSelect v-model="form.activo" label="Estado">
                                <option :value="true">Activo</option>
                                <option :value="false">Inactivo</option>
                            </AppSelect>
                        </div>

                        <!-- Botones del formulario -->
                        <div class="flex gap-3 pt-2">
                            <AppButton type="button" variant="secondary" class="flex-1 py-2.5" @click="cerrarModal">
                                Cancelar
                            </AppButton>
                            <AppButton type="submit" variant="danger" class="flex-1 py-2.5" :loading="form.processing">
                                Guardar
                            </AppButton>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

    </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout  from '@/Layouts/AdminLayout.vue';
import Pagination   from '@/Components/Pagination.vue';
import AppButton    from '@/Components/AppButton.vue';
import AppInput     from '@/Components/AppInput.vue';
import AppSelect    from '@/Components/AppSelect.vue';
import AppBadge     from '@/Components/AppBadge.vue';
import { useConfirm } from '@/Composables/useConfirm';

// Prop principal: colección paginada de usuarios desde el servidor
const props = defineProps({ usuarios: Object });

// Composable para confirmaciones sin window.confirm()
const { confirmar } = useConfirm();

// Estado reactivo del modal (modo + visibilidad)
const modal = reactive({ show: false, modo: 'crear', userId: null });

// Formulario Inertia con los campos del usuario
const form = useForm({
    name:                 '',
    email:                '',
    password:             '',
    password_confirmation: '',
    role:                 'trabajador',
    activo:               true,
});

/** Abre el modal en modo creación */
const abrirCrear = () => {
    form.reset();
    form.clearErrors();
    modal.modo   = 'crear';
    modal.userId = null;
    modal.show   = true;
};

/** Abre el modal en modo edición con los datos del usuario seleccionado */
const abrirEditar = (u) => {
    form.reset();
    form.clearErrors();
    form.name   = u.name;
    form.email  = u.email;
    form.role   = u.role;
    form.activo = u.activo;
    modal.modo   = 'editar';
    modal.userId = u.id;
    modal.show   = true;
};

/** Cierra el modal sin guardar */
const cerrarModal = () => { modal.show = false; };

/** Envía el formulario (POST para crear, PUT para editar) */
const guardar = () => {
    const opciones = { preserveScroll: true, onSuccess: cerrarModal };
    if (modal.modo === 'crear') {
        form.post(route('admin.usuarios.store'), opciones);
    } else {
        form.put(route('admin.usuarios.update', modal.userId), opciones);
    }
};

/** Cambia el estado activo/inactivo del usuario con confirmación previa */
const cambiarEstado = async (u) => {
    const accion = u.activo ? 'desactivar' : 'activar';
    const ok = await confirmar(
        `¿Deseas ${accion} al usuario "${u.name}"?`,
        `${accion.charAt(0).toUpperCase() + accion.slice(1)} usuario`
    );
    if (!ok) return;

    router.patch(route('admin.usuarios.toggle-activo', u.id), {}, { preserveScroll: true });
};

/** Elimina el usuario con confirmación previa */
const eliminar = async (u) => {
    const ok = await confirmar(
        `¿Eliminar al usuario "${u.name}"? Esta acción no se puede deshacer.`,
        'Eliminar usuario'
    );
    if (!ok) return;

    router.delete(route('admin.usuarios.destroy', u.id), { preserveScroll: true });
};
</script>
