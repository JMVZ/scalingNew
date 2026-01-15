<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    clientes: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const estatus = ref(props.filters.estatus ?? '');
const confirmModal = ref(null);
const clienteToDelete = ref(null);

watch([search, estatus], () => {
    router.get(route('clientes.index'), {
        search: search.value,
        estatus: estatus.value !== '' ? estatus.value : null,
    }, {
        preserveState: true,
        replace: true,
    });
}, { debounce: 300 });

const askDeleteCliente = (cliente) => {
    clienteToDelete.value = cliente;
    confirmModal.value.show({
        title: '¿Eliminar Cliente?',
        message: `¿Estás seguro de eliminar al cliente "${cliente.nombre_fiscal}"? Esta acción no se puede deshacer.`,
        onConfirm: () => deleteCliente()
    });
};

const deleteCliente = () => {
    if (clienteToDelete.value) {
        router.delete(route('clientes.destroy', clienteToDelete.value.id), {
            preserveScroll: true,
        });
        clienteToDelete.value = null;
    }
};
</script>

<template>
    <Head title="Clientes" />
    
    <ConfirmModal ref="confirmModal" />

    <AppLayout>
        <template #header>Clientes</template>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900">Gestión de Clientes</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Administra el catálogo de clientes de tu empresa
                </p>
            </div>
            <Link
                :href="route('clientes.create')"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Cliente
            </Link>
        </div>

        <div class="mb-6 rounded-2xl border border-zinc-200 bg-white p-4 shadow">
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Buscar</label>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Número, nombre fiscal, RFC..."
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Estatus</label>
                    <select
                        v-model="estatus"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    >
                        <option value="">Todos</option>
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <div class="rounded-lg bg-zinc-100 px-4 py-2">
                        <p class="text-xs text-zinc-600">Total de clientes</p>
                        <p class="text-2xl font-bold text-zinc-900">{{ clientes.total }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Nombre Fiscal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Nombre Comercial</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">RFC</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Teléfono</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Correo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Estatus</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="cliente in clientes.data"
                            :key="cliente.id"
                            class="border-b border-zinc-100 hover:bg-zinc-50"
                        >
                            <td class="px-6 py-4">
                                <p class="font-medium text-zinc-900">{{ cliente.numero_cliente }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-zinc-900">{{ cliente.nombre_fiscal }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ cliente.nombre_comercial || '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ cliente.rfc || '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ cliente.telefono || '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ cliente.correo || '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        cliente.estatus === 'activo'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700',
                                        'inline-flex rounded-full px-2 py-1 text-xs font-medium'
                                    ]"
                                >
                                    {{ cliente.estatus === 'activo' ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('clientes.edit', cliente.id)"
                                        class="rounded-lg bg-blue-100 p-2 text-blue-600 transition hover:bg-blue-200"
                                        title="Editar"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button
                                        @click="askDeleteCliente(cliente)"
                                        class="rounded-lg bg-red-100 p-2 text-red-600 transition hover:bg-red-200"
                                        title="Eliminar"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="clientes.data.length === 0">
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-12 w-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="text-zinc-500">No se encontraron clientes</p>
                                    <Link
                                        :href="route('clientes.create')"
                                        class="mt-2 text-sm text-[#7c40ff] hover:underline"
                                    >
                                        Crear el primer cliente
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="clientes.data.length > 0" class="border-t border-zinc-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-600">
                        Mostrando {{ clientes.from }} a {{ clientes.to }} de {{ clientes.total }} clientes
                    </p>
                    <div class="flex gap-2">
                        <template v-for="link in clientes.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    link.active
                                        ? 'bg-[#7c40ff] text-white'
                                        : 'bg-white text-zinc-700 hover:bg-zinc-100',
                                    'rounded-lg border border-zinc-300 px-3 py-1 text-sm'
                                ]"
                                v-html="link.label"
                                preserve-scroll
                            />
                            <span
                                v-else
                                :class="[
                                    link.active
                                        ? 'bg-[#7c40ff] text-white'
                                        : 'bg-zinc-100 text-zinc-400',
                                    'rounded-lg border border-zinc-300 px-3 py-1 text-sm cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
