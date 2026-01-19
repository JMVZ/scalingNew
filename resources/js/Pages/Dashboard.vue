<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    systemInfo: Object,
});

const isDev = computed(() => props.systemInfo?.app_env === 'local');
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="rounded-xl bg-gradient-to-br from-purple-500 to-purple-700 p-2 shadow-lg">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900">Dashboard</h1>
                    <p class="text-sm text-zinc-500">Información del sistema</p>
                </div>
            </div>
        </template>

        <div v-if="isDev" class="space-y-6">
            <!-- Información del Sistema -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-purple-50 to-white p-6">
                    <h2 class="text-xl font-bold text-zinc-900">Información del Sistema</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Laravel</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.laravel_version || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">PHP</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.php_version || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Entorno</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.app_env || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Debug</p>
                            <p class="mt-1 text-lg font-bold" :class="systemInfo?.app_debug ? 'text-green-600' : 'text-red-600'">
                                {{ systemInfo?.app_debug ? 'Activado' : 'Desactivado' }}
                            </p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">URL</p>
                            <p class="mt-1 truncate text-sm font-bold text-zinc-900">{{ systemInfo?.app_url || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Timezone</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.timezone || 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Base de Datos -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-blue-50 to-white p-6">
                    <h2 class="text-xl font-bold text-zinc-900">Base de Datos</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Driver</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.db_driver || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Base de Datos</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.db_database || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Host</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.db_host || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Puerto</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.db_port || 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cache y Sesiones -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-green-50 to-white p-6">
                    <h2 class="text-xl font-bold text-zinc-900">Cache y Sesiones</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Cache Driver</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.cache_driver || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Session Driver</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.session_driver || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Queue Driver</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.queue_driver || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Broadcast Driver</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.broadcast_driver || 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Usuario -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-purple-50 to-white p-6">
                    <h2 class="text-xl font-bold text-zinc-900">Usuario Actual</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Nombre</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.user_name || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Email</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.user_email || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Empresa</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.empresa_nombre || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">ID Empresa</p>
                            <p class="mt-1 text-lg font-bold text-zinc-900">{{ systemInfo?.empresa_id || 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rutas Disponibles -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-orange-50 to-white p-6">
                    <h2 class="text-xl font-bold text-zinc-900">Rutas Disponibles</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        <div v-for="route in systemInfo?.routes || []" :key="route.name" class="flex items-center justify-between rounded-lg border border-zinc-200 bg-zinc-50 p-3">
                            <div class="flex-1">
                                <p class="font-semibold text-zinc-900">{{ route.name || 'Sin nombre' }}</p>
                                <p class="text-sm text-zinc-600">{{ route.uri }}</p>
                            </div>
                            <div class="ml-4">
                                <span class="rounded-full bg-purple-100 px-3 py-1 text-xs font-bold text-purple-700">
                                    {{ route.method }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del Servidor -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-indigo-50 to-white p-6">
                    <h2 class="text-xl font-bold text-zinc-900">Servidor</h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Sistema Operativo</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.server_os || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Servidor Web</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.server_software || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Memoria Límite</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.memory_limit || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Tiempo Máximo de Ejecución</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.max_execution_time || 'N/A' }}s</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Upload Máximo</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.upload_max_filesize || 'N/A' }}</p>
                        </div>
                        <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase text-zinc-500">Post Máximo</p>
                            <p class="mt-1 text-sm font-bold text-zinc-900">{{ systemInfo?.post_max_size || 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="rounded-2xl border border-zinc-200 bg-white p-12 text-center shadow-lg">
            <svg class="mx-auto h-16 w-16 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-4 text-xl font-bold text-zinc-900">Modo Producción</h3>
            <p class="mt-2 text-zinc-600">La información del sistema solo está disponible en modo desarrollo.</p>
        </div>
    </AppLayout>
</template>
