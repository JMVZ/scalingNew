<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import LoadingOverlay from '@/Components/LoadingOverlay.vue';

const page = usePage();
const showDatosMaestrosMenu = ref(false);
const showOperacionesMenu = ref(false);

// Detectar URL actual
const currentPath = computed(() => page.url);

const isActive = (path) => {
    if (path === '/dashboard') {
        return currentPath.value === '/dashboard';
    }
    return currentPath.value.startsWith(path);
};

const isDatosMaestrosActive = computed(() => {
    const datosMaestrosPaths = [
        '/clientes', '/proveedores', '/unidades', '/operadores', '/tarifas', '/bancos',
        '/cuentas-bancarias', '/conceptos', '/impuestos', '/centros-costo'
    ];
    return datosMaestrosPaths.some(path => currentPath.value.startsWith(path));
});

const isOperacionesActive = computed(() => {
    const operacionesPaths = ['/ordenes-carga'];
    return operacionesPaths.some(path => currentPath.value.startsWith(path));
});
</script>

<template>
    <FlashMessages />
    <LoadingOverlay />
    
    <div class="flex min-h-screen bg-[#f6f7fb]">
        <!-- Sidebar - Solo iconos -->
        <aside class="fixed left-0 top-0 z-40 h-screen w-20 bg-white shadow-lg">
            <div class="flex h-full flex-col">
                <!-- Logo -->
                <div class="flex h-20 items-center justify-center border-b border-zinc-200">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-[#7c40ff] to-[#b436ff] text-xl font-bold text-white shadow-lg">
                        T
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 p-3">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <div class="group relative">
                            <Link
                                href="/dashboard"
                                :class="[
                                    isActive('/dashboard')
                                        ? 'bg-gradient-to-r from-[#7c40ff] to-[#b436ff] text-white shadow-lg'
                                        : 'text-zinc-700 hover:bg-zinc-100',
                                    'flex h-12 w-12 items-center justify-center rounded-lg transition-all'
                                ]"
                            >
                                <svg class="h-5 w-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </Link>
                            <!-- Tooltip -->
                            <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm text-white shadow-xl">
                                    Dashboard
                                </div>
                                <div class="absolute -left-1 top-1/2 -translate-y-1/2 h-2 w-2 rotate-45 bg-zinc-900"></div>
                            </div>
                        </div>

                        <!-- Operaciones -->
                        <div class="group relative">
                            <button
                                @click="showOperacionesMenu = !showOperacionesMenu; showDatosMaestrosMenu = false;"
                                :class="[
                                    isOperacionesActive || showOperacionesMenu
                                        ? 'bg-gradient-to-r from-[#7c40ff] to-[#b436ff] text-white shadow-lg'
                                        : 'text-zinc-700 hover:bg-zinc-100',
                                    'flex h-12 w-12 items-center justify-center rounded-lg transition-all'
                                ]"
                            >
                                <svg class="h-5 w-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </button>
                            <!-- Tooltip -->
                            <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm text-white shadow-xl">
                                    Operaciones
                                </div>
                                <div class="absolute -left-1 top-1/2 -translate-y-1/2 h-2 w-2 rotate-45 bg-zinc-900"></div>
                            </div>
                        </div>

                        <!-- Datos Maestros -->
                        <div class="group relative">
                            <button
                                @click="showDatosMaestrosMenu = !showDatosMaestrosMenu; showOperacionesMenu = false;"
                                :class="[
                                    isDatosMaestrosActive || showDatosMaestrosMenu
                                        ? 'bg-gradient-to-r from-[#7c40ff] to-[#b436ff] text-white shadow-lg'
                                        : 'text-zinc-700 hover:bg-zinc-100',
                                    'flex h-12 w-12 items-center justify-center rounded-lg transition-all'
                                ]"
                            >
                                <svg class="h-5 w-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </button>
                            <!-- Tooltip -->
                            <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                                <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm text-white shadow-xl">
                                    Datos Maestros
                                </div>
                                <div class="absolute -left-1 top-1/2 -translate-y-1/2 h-2 w-2 rotate-45 bg-zinc-900"></div>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- User Info -->
                <div class="group relative border-t border-zinc-200 p-4">
                    <div class="flex justify-center">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#7c40ff] text-sm font-bold text-white">
                            {{ page.props.auth.user.name.charAt(0).toUpperCase() }}
                        </div>
                    </div>
                    
                    <!-- Tooltip de usuario -->
                    <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-50">
                        <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm text-white shadow-xl">
                            <p class="font-medium">{{ page.props.auth.user.name }}</p>
                            <p class="text-xs text-zinc-300" v-if="page.props.auth.user.empresa">
                                {{ page.props.auth.user.empresa.nombre }}
                            </p>
                        </div>
                        <div class="absolute -left-1 top-1/2 -translate-y-1/2 h-2 w-2 rotate-45 bg-zinc-900"></div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay para cerrar menús al hacer clic fuera -->
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showDatosMaestrosMenu || showOperacionesMenu"
                @click="showDatosMaestrosMenu = false; showOperacionesMenu = false;"
                class="fixed inset-0 z-20 bg-black/20 backdrop-blur-sm"
            ></div>
        </Transition>

        <!-- Panel de Datos Maestros -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-full"
        >
            <div
                v-if="showDatosMaestrosMenu"
                class="fixed left-20 top-0 z-30 h-screen w-80 bg-white shadow-2xl border-r border-zinc-200"
            >
                <div class="flex h-full flex-col">
                    <!-- Header del Panel -->
                    <div class="border-b border-zinc-200 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-zinc-900">Datos Maestros</h2>
                            <button
                                @click="showDatosMaestrosMenu = false"
                                class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 transition"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">Catálogos base del sistema</p>
                    </div>

                    <!-- Contenido del Panel -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-6">
                            <!-- Sección: Clientes y Proveedores -->
                            <div>
                                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">Entidades</h3>
                                <div class="space-y-1">
                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/clientes"
                                        :class="[
                                            isActive('/clientes')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span class="text-sm">Clientes</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/proveedores"
                                        :class="[
                                            isActive('/proveedores')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-sm">Proveedores</span>
                                    </Link>
                                </div>
                            </div>

                            <hr class="border-zinc-200" />

                            <!-- Sección: Recursos -->
                            <div>
                                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">Recursos</h3>
                                <div class="space-y-1">
                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/unidades"
                                        :class="[
                                            isActive('/unidades')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                        <span class="text-sm">Unidades</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/operadores"
                                        :class="[
                                            isActive('/operadores')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-sm">Operadores</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/tarifas"
                                        :class="[
                                            isActive('/tarifas')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm">Tarifas</span>
                                    </Link>
                                </div>
                            </div>

                            <hr class="border-zinc-200" />

                            <!-- Sección: Finanzas -->
                            <div>
                                <h3 class="mb-3 text-xs font-bold uppercase tracking-wider text-zinc-500">Finanzas</h3>
                                <div class="space-y-1">
                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/bancos"
                                        :class="[
                                            isActive('/bancos')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                        <span class="text-sm">Bancos</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/cuentas-bancarias"
                                        :class="[
                                            isActive('/cuentas-bancarias')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        <span class="text-sm">Cuentas Bancarias</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/conceptos"
                                        :class="[
                                            isActive('/conceptos')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                        </svg>
                                        <span class="text-sm">Conceptos</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/impuestos"
                                        :class="[
                                            isActive('/impuestos')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm">Impuestos</span>
                                    </Link>

                                    <Link
                                        @click="showDatosMaestrosMenu = false"
                                        href="/centros-costo"
                                        :class="[
                                            isActive('/centros-costo')
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <span class="text-sm">Centros de Costo</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Panel de Operaciones -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-full"
        >
            <div
                v-if="showOperacionesMenu"
                class="fixed left-20 top-0 z-30 h-screen w-80 bg-white shadow-2xl border-r border-zinc-200"
            >
                <div class="flex h-full flex-col">
                    <!-- Header del Panel -->
                    <div class="border-b border-zinc-200 p-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-zinc-900">Operaciones</h2>
                            <button
                                @click="showOperacionesMenu = false"
                                class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 transition"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-sm text-zinc-500">Gestiona tus operaciones diarias</p>
                    </div>

                    <!-- Contenido del Panel -->
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-4">
                            <!-- Órdenes de Carga -->
                            <Link
                                @click="showOperacionesMenu = false"
                                href="/ordenes-carga"
                                :class="[
                                    isActive('/ordenes-carga')
                                        ? 'bg-gradient-to-r from-[#7c40ff] to-[#b436ff] text-white shadow-lg'
                                        : 'bg-zinc-50 text-zinc-700 hover:bg-zinc-100',
                                    'flex items-center gap-4 rounded-xl p-4 transition-all'
                                ]"
                            >
                                <div class="rounded-lg bg-white/20 p-3">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Órdenes de Carga</h3>
                                    <p class="text-sm opacity-90">Gestión de viajes y operaciones</p>
                                </div>
                            </Link>

                            <div class="rounded-lg bg-zinc-50 p-4 text-center text-sm text-zinc-500">
                                <p>Más módulos operativos próximamente...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Main Content Area -->
        <div class="ml-20 flex-1">
            <!-- Header -->
            <header class="border-b border-zinc-200 bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4">
                    <h1 class="text-2xl font-bold text-zinc-900">
                        <slot name="header">Dashboard</slot>
                    </h1>
                    
                    <!-- User Menu -->
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-zinc-600">
                            {{ page.props.auth.user.name }}
                        </span>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="rounded-lg bg-zinc-100 px-4 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-200"
                        >
                            Cerrar Sesión
                        </Link>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>
