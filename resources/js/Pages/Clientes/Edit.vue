<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    cliente: Object,
});

const form = useForm({
    numero_cliente: props.cliente.numero_cliente,
    nombre_fiscal: props.cliente.nombre_fiscal,
    nombre_comercial: props.cliente.nombre_comercial || '',
    rfc: props.cliente.rfc || '',
    estatus: props.cliente.estatus,
    fecha_alta: props.cliente.fecha_alta ? props.cliente.fecha_alta.split('T')[0] : '',
    direccion: props.cliente.direccion || '',
    telefono: props.cliente.telefono || '',
    correo: props.cliente.correo || '',
});

const submit = () => {
    form.put(route('clientes.update', props.cliente.id));
};
</script>

<template>
    <Head title="Editar Cliente" />

    <AppLayout>
        <template #header>Editar Cliente</template>

        <div class="mb-6">
            <Link
                :href="route('clientes.index')"
                class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a Clientes
            </Link>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow">
            <h2 class="mb-6 text-2xl font-bold text-zinc-900">Editar Cliente</h2>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Datos Generales -->
                <div class="border-b border-zinc-200 pb-4">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Datos Generales</h3>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Número de Cliente <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.numero_cliente"
                            type="text"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.numero_cliente" class="mt-1 text-sm text-red-600">
                            {{ form.errors.numero_cliente }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Fecha de Alta
                        </label>
                        <input
                            v-model="form.fecha_alta"
                            type="date"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.fecha_alta" class="mt-1 text-sm text-red-600">
                            {{ form.errors.fecha_alta }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Nombre Fiscal <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nombre_fiscal"
                            type="text"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.nombre_fiscal" class="mt-1 text-sm text-red-600">
                            {{ form.errors.nombre_fiscal }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Nombre Comercial
                        </label>
                        <input
                            v-model="form.nombre_comercial"
                            type="text"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.nombre_comercial" class="mt-1 text-sm text-red-600">
                            {{ form.errors.nombre_comercial }}
                        </p>
                    </div>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            RFC
                        </label>
                        <input
                            v-model="form.rfc"
                            type="text"
                            maxlength="13"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.rfc" class="mt-1 text-sm text-red-600">
                            {{ form.errors.rfc }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Estatus <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.estatus"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        >
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                        <p v-if="form.errors.estatus" class="mt-1 text-sm text-red-600">
                            {{ form.errors.estatus }}
                        </p>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="border-t border-zinc-200 pt-4">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Contacto</h3>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Teléfono
                        </label>
                        <input
                            v-model="form.telefono"
                            type="tel"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.telefono" class="mt-1 text-sm text-red-600">
                            {{ form.errors.telefono }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Correo
                        </label>
                        <input
                            v-model="form.correo"
                            type="email"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.correo" class="mt-1 text-sm text-red-600">
                            {{ form.errors.correo }}
                        </p>
                    </div>
                </div>

                <!-- Dirección -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">
                        Dirección
                    </label>
                    <textarea
                        v-model="form.direccion"
                        rows="3"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    ></textarea>
                    <p v-if="form.errors.direccion" class="mt-1 text-sm text-red-600">
                        {{ form.errors.direccion }}
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-4 pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-5 w-5 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? 'Actualizando...' : 'Actualizar Cliente' }}
                    </button>

                    <Link
                        :href="route('clientes.index')"
                        class="rounded-xl border border-zinc-300 px-6 py-3 font-semibold text-zinc-700 transition hover:bg-zinc-50"
                    >
                        Cancelar
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
