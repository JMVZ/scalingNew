<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    conceptos: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const tipo = ref(props.filters.tipo || '');
const activo = ref(props.filters.activo ?? '');
const confirmModal = ref(null);
const itemToDelete = ref(null);

watch([search, tipo, activo], () => {
    router.get(route('conceptos.index'), {
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
        title: '¿Eliminar Concepto?',
        message: `¿Estás seguro de eliminar el concepto "${item.nombre}"? Esta acción no se puede deshacer.`,
        onConfirm: () => deleteItem()
    });
};

const deleteItem = () => {
    if (itemToDelete.value) {
        router.delete(route('conceptos.destroy', itemToDelete.value.id), {
            preserveScroll: true,
        });
        itemToDelete.value = null;
    }
};
</script>

<template>
    <Head title="Conceptos" />
    
    <ConfirmModal ref="confirmModal" />

    <AppLayout>
        <template #header>Conceptos</template>

        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-zinc-900">Gestión de Conceptos</h1>
                <p class="mt-1 text-sm text-zinc-600">
                    Administra los conceptos de ingresos y gastos
                </p>
            </div>
            <Link
                :href="route('conceptos.create')"
                class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Concepto
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
                        <option value="Ingreso">Ingreso</option>
                        <option value="Egreso">Egreso</option>
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
                        <p class="text-xs text-zinc-600">Total de conceptos</p>
                        <p class="text-2xl font-bold text-zinc-900">{{ conceptos.total }}</p>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Categoría</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-zinc-500">IVA</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in conceptos.data"
                            :key="item.id"
                            class="border-b border-zinc-100 hover:bg-zinc-50"
                        >
                            <td class="px-6 py-4">
                                <p class="font-mono font-medium text-zinc-900">{{ item.codigo }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-zinc-900">{{ item.nombre }}</p>
                                <p v-if="item.descripcion" class="text-xs text-zinc-500 line-clamp-1">{{ item.descripcion }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    :class="[
                                        item.tipo === 'Ingreso'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-blue-100 text-blue-700',
                                        'inline-flex rounded-full px-2 py-1 text-xs font-medium'
                                    ]"
                                >
                                    {{ item.tipo }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-zinc-600">
                                {{ item.categoria || '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span v-if="item.aplica_iva" class="text-green-600">✓</span>
                                <span v-else class="text-zinc-300">—</span>
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
                                        :href="route('conceptos.edit', item.id)"
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

                        <tr v-if="conceptos.data.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="h-12 w-12 text-zinc-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                    </svg>
                                    <p class="text-zinc-500">No se encontraron conceptos</p>
                                    <Link
                                        :href="route('conceptos.create')"
                                        class="mt-2 text-sm text-[#7c40ff] hover:underline"
                                    >
                                        Registrar el primer concepto
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="conceptos.data.length > 0" class="border-t border-zinc-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-zinc-600">
                        Mostrando {{ conceptos.from }} a {{ conceptos.to }} de {{ conceptos.total }} conceptos
                    </p>
                    <div class="flex gap-2">
                        <template v-for="link in conceptos.links" :key="link.label">
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
