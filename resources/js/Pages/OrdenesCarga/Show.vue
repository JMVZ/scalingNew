<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    orden: Object,
});

const confirmModal = ref(null);
const nuevoEstadoTemp = ref('');

const askCambiarEstado = (nuevoEstado) => {
    nuevoEstadoTemp.value = nuevoEstado;
    const estadoLabel = {
        'planeacion': 'Planeación',
        'en_ejecucion': 'En Ejecución',
        'finalizado': 'Finalizado'
    }[nuevoEstado];
    
    confirmModal.value.show({
        title: '¿Cambiar Estado?',
        message: `¿Deseas cambiar el estado de la orden ${props.orden.folio} a "${estadoLabel}"?`,
        onConfirm: () => cambiarEstado()
    });
};

const cambiarEstado = () => {
    router.patch(route('ordenes-carga.cambiar-estado', props.orden.id), {
        estado: nuevoEstadoTemp.value
    }, {
        preserveScroll: true,
    });
};

const getEstadoBadgeClass = (estado) => {
    const classes = {
        'planeacion': 'bg-blue-100 text-blue-700 border-blue-200',
        'en_ejecucion': 'bg-amber-100 text-amber-700 border-amber-200',
        'finalizado': 'bg-green-100 text-green-700 border-green-200',
    };
    return classes[estado] || 'bg-zinc-100 text-zinc-700';
};

const getEstadoLabel = (estado) => {
    const labels = {
        'planeacion': 'Planeación',
        'en_ejecucion': 'En Ejecución',
        'finalizado': 'Finalizado',
    };
    return labels[estado] || estado;
};
</script>

<template>
    <Head :title="`Orden ${orden.folio}`" />
    
    <ConfirmModal ref="confirmModal" />

    <AppLayout>
        <template #header>Detalle de Orden</template>

        <div class="mb-6 flex items-center justify-between">
            <Link
                :href="route('ordenes-carga.index')"
                class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a Órdenes
            </Link>

            <div class="flex items-center gap-3">
                <Link
                    :href="route('ordenes-carga.edit', orden.id)"
                    class="flex items-center gap-2 rounded-xl border border-zinc-300 px-4 py-2 font-semibold text-zinc-700 transition hover:bg-zinc-50"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar
                </Link>
            </div>
        </div>

        <!-- Header de la orden -->
        <div class="mb-6 rounded-2xl border border-zinc-200 bg-gradient-to-r from-[#7c40ff] to-[#b436ff] p-6 text-white shadow-lg">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold">{{ orden.folio }}</h1>
                    <p class="mt-2 text-white/90">
                        Creado el {{ new Date(orden.created_at).toLocaleDateString('es-MX', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                        }) }}
                    </p>
                </div>
                <div>
                    <span
                        :class="[
                            getEstadoBadgeClass(orden.estado),
                            'inline-flex rounded-full border px-4 py-2 text-sm font-bold'
                        ]"
                    >
                        {{ getEstadoLabel(orden.estado) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Columna Principal -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Cliente -->
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">Información del Cliente</h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-zinc-500">Nombre</p>
                            <p class="text-lg font-semibold text-zinc-900">{{ orden.cliente.nombre }}</p>
                        </div>
                    </div>
                </div>

                <!-- Servicio -->
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">Información del Servicio</h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-zinc-500">Tipo de Servicio</p>
                            <p class="font-medium text-zinc-900">{{ orden.tipo_servicio }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-zinc-500">Ruta</p>
                            <p class="font-medium text-zinc-900">{{ orden.ruta || '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-zinc-500">Tarifa</p>
                            <p class="text-xl font-bold text-zinc-900">
                                ${{ Number(orden.tarifa).toLocaleString('es-MX', { minimumFractionDigits: 2 }) }}
                                <span class="text-sm font-normal text-zinc-500">{{ orden.moneda }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Origen y Destino -->
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">Ruta</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Origen</p>
                                    <p class="font-semibold text-zinc-900">{{ orden.origen }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <svg class="h-6 w-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-zinc-500">Destino</p>
                                    <p class="font-semibold text-zinc-900">{{ orden.destino }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unidad y Operador -->
                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Unidad -->
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                        <h2 class="mb-4 text-lg font-bold text-zinc-900">Unidad</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-zinc-500">Tipo</p>
                                <p class="font-medium text-zinc-900">{{ orden.unidad_texto || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500">Placas</p>
                                <p class="font-medium text-zinc-900">{{ orden.placas_unidad || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500">Remolque</p>
                                <p class="font-medium text-zinc-900">{{ orden.remolque || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500">Placas Remolque</p>
                                <p class="font-medium text-zinc-900">{{ orden.placas_remolque || '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Operador -->
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                        <h2 class="mb-4 text-lg font-bold text-zinc-900">Operador</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-zinc-500">Nombre</p>
                                <p class="font-medium text-zinc-900">{{ orden.operador_texto || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-zinc-500">Licencia</p>
                                <p class="font-medium text-zinc-900">{{ orden.licencia_operador || '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mercancía -->
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">Descripción de la Mercancía</h2>
                    <p class="text-zinc-700">{{ orden.descripcion_mercancia || 'Sin descripción' }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Cambiar Estado -->
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">Cambiar Estado</h2>
                    <div class="space-y-2">
                        <button
                            v-if="orden.estado !== 'planeacion'"
                            @click="askCambiarEstado('planeacion')"
                            class="flex w-full items-center gap-2 rounded-lg border-2 border-blue-200 bg-blue-50 px-4 py-3 text-left font-medium text-blue-700 transition hover:bg-blue-100"
                        >
                            <div class="h-3 w-3 rounded-full bg-blue-600"></div>
                            Planeación
                        </button>
                        <button
                            v-if="orden.estado !== 'en_ejecucion'"
                            @click="askCambiarEstado('en_ejecucion')"
                            class="flex w-full items-center gap-2 rounded-lg border-2 border-amber-200 bg-amber-50 px-4 py-3 text-left font-medium text-amber-700 transition hover:bg-amber-100"
                        >
                            <div class="h-3 w-3 rounded-full bg-amber-600"></div>
                            En Ejecución
                        </button>
                        <button
                            v-if="orden.estado !== 'finalizado'"
                            @click="askCambiarEstado('finalizado')"
                            class="flex w-full items-center gap-2 rounded-lg border-2 border-green-200 bg-green-50 px-4 py-3 text-left font-medium text-green-700 transition hover:bg-green-100"
                        >
                            <div class="h-3 w-3 rounded-full bg-green-600"></div>
                            Finalizado
                        </button>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow">
                    <h2 class="mb-4 text-lg font-bold text-zinc-900">Información</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-zinc-500">Empresa</p>
                            <p class="font-medium text-zinc-900">{{ orden.empresa.nombre }}</p>
                        </div>
                        <div>
                            <p class="text-zinc-500">Fecha de Creación</p>
                            <p class="font-medium text-zinc-900">
                                {{ new Date(orden.created_at).toLocaleDateString('es-MX') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-zinc-500">Última Actualización</p>
                            <p class="font-medium text-zinc-900">
                                {{ new Date(orden.updated_at).toLocaleDateString('es-MX') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
