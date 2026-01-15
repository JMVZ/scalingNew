<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    clientes: Array,
});

const form = useForm({
    cliente_id: '',
    tipo_servicio: '',
    ruta: '',
    tarifa: '',
    moneda: 'MXN',
    origen: '',
    destino: '',
    unidad_texto: '',
    placas_unidad: '',
    operador_texto: '',
    licencia_operador: '',
    remolque: '',
    placas_remolque: '',
    descripcion_mercancia: '',
    estado: 'planeacion',
});

const submit = () => {
    form.post(route('ordenes-carga.store'));
};
</script>

<template>
    <Head title="Crear Orden de Carga" />

    <AppLayout>
        <template #header>Crear Orden de Carga</template>

        <div class="mb-6">
            <Link
                :href="route('ordenes-carga.index')"
                class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a Órdenes
            </Link>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow">
            <h2 class="mb-6 text-2xl font-bold text-zinc-900">Nueva Orden de Carga</h2>
            <p class="mb-6 text-sm text-zinc-600">El folio se generará automáticamente</p>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Información del Cliente -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Información del Cliente</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">
                                Cliente <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.cliente_id"
                                required
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            >
                                <option value="">Selecciona un cliente</option>
                                <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                                    {{ cliente.nombre }}
                                </option>
                            </select>
                            <p v-if="form.errors.cliente_id" class="mt-1 text-sm text-red-600">{{ form.errors.cliente_id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información del Servicio -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Información del Servicio</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">
                                Tipo de Servicio <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.tipo_servicio"
                                type="text"
                                required
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Ej: Carga Completa, Carga Parcial"
                            />
                            <p v-if="form.errors.tipo_servicio" class="mt-1 text-sm text-red-600">{{ form.errors.tipo_servicio }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Ruta</label>
                            <input
                                v-model="form.ruta"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Ej: CDMX - Guadalajara"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">
                                Tarifa <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.tarifa"
                                type="number"
                                step="0.01"
                                required
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="0.00"
                            />
                            <p v-if="form.errors.tarifa" class="mt-1 text-sm text-red-600">{{ form.errors.tarifa }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">
                                Moneda <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.moneda"
                                required
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            >
                                <option value="MXN">MXN (Pesos Mexicanos)</option>
                                <option value="USD">USD (Dólares)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Origen y Destino -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Origen y Destino</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">
                                Origen <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.origen"
                                type="text"
                                required
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Ciudad de origen"
                            />
                            <p v-if="form.errors.origen" class="mt-1 text-sm text-red-600">{{ form.errors.origen }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">
                                Destino <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.destino"
                                type="text"
                                required
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Ciudad de destino"
                            />
                            <p v-if="form.errors.destino" class="mt-1 text-sm text-red-600">{{ form.errors.destino }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de la Unidad -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Información de la Unidad</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Unidad</label>
                            <input
                                v-model="form.unidad_texto"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Ej: Torton, Rabon, Trailer"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Placas de Unidad</label>
                            <input
                                v-model="form.placas_unidad"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="ABC-123-D"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Remolque</label>
                            <input
                                v-model="form.remolque"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Tipo de remolque"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Placas de Remolque</label>
                            <input
                                v-model="form.placas_remolque"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="XYZ-456-R"
                            />
                        </div>
                    </div>
                </div>

                <!-- Información del Operador -->
                <div>
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Información del Operador</h3>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Nombre del Operador</label>
                            <input
                                v-model="form.operador_texto"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Nombre completo"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-zinc-700 mb-1">Licencia del Operador</label>
                            <input
                                v-model="form.licencia_operador"
                                type="text"
                                class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                                placeholder="Número de licencia"
                            />
                        </div>
                    </div>
                </div>

                <!-- Descripción de Mercancía -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Descripción de la Mercancía</label>
                    <textarea
                        v-model="form.descripcion_mercancia"
                        rows="3"
                        class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        placeholder="Describe la mercancía a transportar"
                    ></textarea>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-4 pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <!-- Spinner cuando está procesando -->
                        <svg
                            v-if="form.processing"
                            class="h-5 w-5 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <!-- Icono normal -->
                        <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? 'Creando...' : 'Crear Orden de Carga' }}
                    </button>

                    <Link
                        :href="route('ordenes-carga.index')"
                        class="rounded-xl border border-zinc-300 px-6 py-3 font-semibold text-zinc-700 transition hover:bg-zinc-50"
                    >
                        Cancelar
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
