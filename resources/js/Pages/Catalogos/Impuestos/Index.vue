<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    impuestos: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const tipo = ref(props.filters.tipo || '');
const activo = ref(props.filters.activo ?? '');
const confirmModal = ref(null);
const itemToDelete = ref(null);

watch([search, tipo, activo], () => {
    router.get(route('impuestos.index'), {
        search: search.value,
        tipo: tipo.value || null,
        activo: activo.value !== '' ? activo.value : null,
    }, {
        preserveState: true,
        replace: true,
    });
}, { debounce: 300 });

const askDelete = (item) => {
    itemToDelete.value = item;
    confirmModal.value.show({
        title: '¿Eliminar Impuesto?',
        message: `¿Estás seguro de eliminar el impuesto "${item.nombre}"? Esta acción no se puede deshacer.`,
        onConfirm: () => deleteItem()
    });
};

const deleteItem = () => {
    if (itemToDelete.value) {
        router.delete(route('impuestos.destroy', itemToDelete.value.id), {
            preserveScroll: true,
        });
        itemToDelete.value = null;
    }
};
</script>

<template>
    <Head title="Impuestos" />
    
    <ConfirmModal ref="confirmModal" />

    <AppLayout>
        <template #header>Impuestos</template>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900">Gestión de Impuestos</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Administra las tasas impositivas aplicables
                </p>
            </div>
            <Link
                :href="route('impuestos.create')"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Impuesto
            </Link>
        </div>

        <div class="mb-6 rounded-2xl border border-zinc-200 bg-white p-4 shadow">
            <div class="grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Buscar</label>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Código, nombre..."
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Tipo</label>
                    <select
                        v-model="tipo"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    >
                        <option value="">Todos</option>
                        <option value="IVA">IVA</option>
                        <option value="ISR">ISR</option>
                        <option value="Retención IVA">Retención IVA</option>
                        <option value="Retención ISR">Retención ISR</option>
                        <option value="IEPS">IEPS</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Estado</label>
                    <select
                        v-model="activo"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                    >
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <div class="rounded-lg bg-zinc-100 px-4 py-2">
                        <p class="text-xs text-zinc-600">Total de impuestos</p>
                        <p class="text-2xl font-bold text-zinc-900">{{ impuestos.total }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white shadow">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Código</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Tipo</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500">Tasa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Descripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in impuestos.data"
                            :key="item.id"
                            class="border-b border-zinc-100 hover:bg-zinc-50"
                        >
                            <td class="px-6 py-4">
                                <p class="font-mono font-medium text-zinc-900">{{ item.codigo }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-zinc-900">{{ item.nombre }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        item.tipo.includes('Retención')
                                            ? 'bg-orange-100 text-orange-700'
                                            : 'bg-purple-100 text-purple-700',
                                        'inline-flex rounded-full px-2 py-1 text-xs font-medium'
                                    ]"
                                >
                                    {{ item.tipo }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-zinc-900">
                                {{ (Number(item.tasa) * 100).toFixed(2) }}%
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                <p class="line-clamp-1">{{ item.descripcion || '-' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        item.activo
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700',
                                        'inline-flex rounded-full px-2 py-1 text-xs font-medium'
                                    ]"
                                >
                                    {{ item.activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <Link
                                        :href="route('impuestos.edit', item.id)"
                                        class="rounded-lg bg-blue-100 p-2 text-blue-600 transition hover:bg-blue-200"
                                        title="Editar"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </Link>
                                    <button
                                        @click="askDelete(item)"
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

                        <tr v-if="impuestos.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-12 w-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-zinc-500">No se encontraron impuestos</p>
                                    <Link
                                        :href="route('impuestos.create')"
                                        class="mt-2 text-sm text-[#7c40ff] hover:underline"
                                    >
                                        Registrar el primer impuesto
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="impuestos.data.length > 0" class="border-t border-zinc-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-600">
                        Mostrando {{ impuestos.from }} a {{ impuestos.to }} de {{ impuestos.total }} impuestos
                    </p>
                    <div class="flex gap-2">
                        <template v-for="link in impuestos.links" :key="link.label">
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
                                    'bg-zinc-100 text-zinc-400 rounded-lg border border-zinc-300 px-3 py-1 text-sm cursor-not-allowed'
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
