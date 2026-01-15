<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    ordenes: Object,
    clientes: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');
const estado = ref(props.filters.estado || '');
const clienteId = ref(props.filters.cliente_id || '');
const confirmModal = ref(null);
const ordenToDelete = ref(null);

// Watch para buscar en tiempo real
watch([search, estado, clienteId], () => {
    router.get(route('ordenes-carga.index'), {
        search: search.value,
        estado: estado.value || null,
        cliente_id: clienteId.value || null,
    }, {
        preserveState: true,
        replace: true,
    });
}, { debounce: 300 });

const askDeleteOrden = (orden) => {
    ordenToDelete.value = orden;
    confirmModal.value.show({
        title: '¿Eliminar Orden de Carga?',
        message: `¿Estás seguro de eliminar la orden ${orden.folio}? Esta acción no se puede deshacer.`,
        onConfirm: () => deleteOrden()
    });
};

const deleteOrden = () => {
    if (ordenToDelete.value) {
        router.delete(route('ordenes-carga.destroy', ordenToDelete.value.id), {
            preserveScroll: true,
        });
        ordenToDelete.value = null;
    }
};

const getEstadoBadgeClass = (estadoValue) => {
    const classes = {
        'planeacion': 'bg-blue-100 text-blue-700',
        'en_ejecucion': 'bg-amber-100 text-amber-700',
        'finalizado': 'bg-green-100 text-green-700',
    };
    return classes[estadoValue] || 'bg-zinc-100 text-zinc-700';
};

const getEstadoLabel = (estadoValue) => {
    const labels = {
        'planeacion': 'Planeación',
        'en_ejecucion': 'En Ejecución',
        'finalizado': 'Finalizado',
    };
    return labels[estadoValue] || estadoValue;
};
</script>

<template>
    <Head title="Órdenes de Carga" />
    
    <ConfirmModal ref="confirmModal" />

    <AppLayout>
        <template #header>Órdenes de Carga</template>

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900">Órdenes de Carga</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Gestiona los viajes y operaciones de transporte
                </p>
            </div>
            <Link
                :href="route('ordenes-carga.create')"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0-6H6" />
                </svg>
                Nueva Orden
            </Link>
        </div>

        <!-- Filtros -->
        <div class="mb-6 rounded-2xl border border-zinc-200 bg-white p-4 shadow">
            <div class="grid gap-4 md:grid-cols-4">
                <!-- Búsqueda -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Buscar</label>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Folio, origen, destino..."
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    />
                </div>

                <!-- Estado -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Estado</label>
                    <select
                        v-model="estado"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    >
                        <option value="">Todos</option>
                        <option value="planeacion">Planeación</option>
                        <option value="en_ejecucion">En Ejecución</option>
                        <option value="finalizado">Finalizado</option>
                    </select>
                </div>

                <!-- Cliente -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Cliente</label>
                    <select
                        v-model="clienteId"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    >
                        <option value="">Todos</option>
                        <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                            {{ cliente.nombre }}
                        </option>
                    </select>
                </div>

                <!-- Total -->
                <div class="flex items-end">
                    <div class="rounded-lg bg-zinc-100 px-4 py-2">
                        <p class="text-xs text-zinc-600">Total órdenes</p>
                        <p class="text-2xl font-bold text-zinc-900">{{ ordenes.total }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="rounded-2xl border border-zinc-200 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Folio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Ruta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Tarifa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Operador</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="orden in ordenes.data"
                            :key="orden.id"
                            class="border-b border-zinc-100 hover:bg-zinc-50"
                        >
                            <td class="px-6 py-4">
                                <p class="font-medium text-zinc-900">{{ orden.folio }}</p>
                                <p class="text-xs text-zinc-500">{{ new Date(orden.created_at).toLocaleDateString() }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ orden.cliente.nombre }}
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                <p>{{ orden.origen }}</p>
                                <p class="text-xs text-zinc-400">→ {{ orden.destino }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-zinc-900">
                                    ${{ Number(orden.tarifa).toLocaleString('es-MX', { minimumFractionDigits: 2 }) }}
                                </p>
                                <p class="text-xs text-zinc-500">{{ orden.moneda }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ orden.operador_texto || '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        getEstadoBadgeClass(orden.estado),
                                        'inline-flex rounded-full px-2 py-1 text-xs font-medium'
                                    ]"
                                >
                                    {{ getEstadoLabel(orden.estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('ordenes-carga.show', orden.id)"
                                        class="rounded-lg bg-zinc-100 p-2 text-zinc-600 transition hover:bg-zinc-200"
                                        title="Ver"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </Link>
                                    <Link
                                        :href="route('ordenes-carga.edit', orden.id)"
                                        class="rounded-lg bg-blue-100 p-2 text-blue-600 transition hover:bg-blue-200"
                                        title="Editar"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button
                                        v-if="orden.estado === 'planeacion'"
                                        @click="askDeleteOrden(orden)"
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

                        <!-- Si no hay órdenes -->
                        <tr v-if="ordenes.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-12 w-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-zinc-500">No se encontraron órdenes de carga</p>
                                    <Link
                                        :href="route('ordenes-carga.create')"
                                        class="mt-2 text-sm text-[#7c40ff] hover:underline"
                                    >
                                        Crear la primera orden
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div v-if="ordenes.data.length > 0" class="border-t border-zinc-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-600">
                        Mostrando {{ ordenes.from }} a {{ ordenes.to }} de {{ ordenes.total }} órdenes
                    </p>
                    <div class="flex gap-2">
                        <template v-for="link in ordenes.links" :key="link.label">
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
