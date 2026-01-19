<script setup>
import { computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import FlashMessages from '@/Components/FlashMessages.vue';
import LoadingOverlay from '@/Components/LoadingOverlay.vue';
import { useMenu } from '@/composables/useMenu';

const page = usePage();
const { 
    menuConfig, 
    openModules, 
    toggleModule, 
    closeAllModules, 
    isModuleOpen, 
    isModuleActive, 
    isItemActive,
    currentPath 
} = useMenu();

// Auto-abrir módulo activo al cargar (solo uno a la vez)
watch(() => page.url, (newUrl) => {
    const activeModule = menuConfig.modules.find(module => 
        module.items.some(item => {
            if (item.route === '/dashboard') {
                return newUrl === '/dashboard';
            }
            return newUrl.startsWith(item.route);
        })
    );
    if (activeModule) {
        // Cerrar todos los módulos y abrir solo el activo
        openModules.value.clear();
        openModules.value.add(activeModule.id);
    } else {
        // Si no hay módulo activo, cerrar todos
        openModules.value.clear();
    }
}, { immediate: true });

// Detectar si dashboard está activo
const isDashboardActive = computed(() => currentPath.value === '/dashboard');
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
                <nav class="flex-1 p-3 overflow-y-auto">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <div class="group relative">
                            <Link
                                href="/dashboard"
                                :class="[
                                    isDashboardActive
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
                            <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-200 delay-75 z-[60]">
                                <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-2xl">
                                    Dashboard
                                </div>
                                <div class="absolute -left-1 top-1/2 -translate-y-1/2 h-2 w-2 rotate-45 bg-zinc-900"></div>
                            </div>
                        </div>

                        <!-- Módulos del Sistema -->
                        <div
                            v-for="module in menuConfig.modules"
                            :key="module.id"
                            class="group relative"
                        >
                            <button
                                @click="toggleModule(module.id)"
                                :class="[
                                    isModuleActive(module) || isModuleOpen(module.id)
                                        ? 'bg-gradient-to-r from-[#7c40ff] to-[#b436ff] text-white shadow-lg'
                                        : 'text-zinc-700 hover:bg-zinc-100',
                                    'flex h-12 w-12 items-center justify-center rounded-lg transition-all'
                                ]"
                            >
                                <svg class="h-5 w-5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="module.icon" />
                                </svg>
                            </button>
                            <!-- Tooltip -->
                            <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-200 delay-75 z-[60]">
                                <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm font-medium text-white shadow-2xl">
                                    {{ module.name }}
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
                    <div class="pointer-events-none absolute left-full top-1/2 ml-3 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-200 delay-75 z-[60]">
                        <div class="whitespace-nowrap rounded-lg bg-zinc-900 px-3 py-2 text-sm text-white shadow-2xl">
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
                v-if="openModules.size > 0"
                @click="closeAllModules()"
                class="fixed inset-0 z-20 bg-black/20 backdrop-blur-sm"
            ></div>
        </Transition>

        <!-- Paneles de Módulos - Solo uno visible a la vez -->
        <template v-for="module in menuConfig.modules" :key="module.id">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-full"
        >
            <div
                    v-if="isModuleOpen(module.id)"
                class="fixed left-20 top-0 z-30 h-screen w-80 bg-white shadow-2xl border-r border-zinc-200"
            >
                <div class="flex h-full flex-col">
                    <!-- Header del Panel -->
                    <div class="border-b border-zinc-200 p-6">
                        <div class="flex items-center justify-between">
                                <h2 class="text-2xl font-bold text-zinc-900">{{ module.name }}</h2>
                            <button
                                    @click="closeAllModules()"
                                class="rounded-lg p-2 text-zinc-500 hover:bg-zinc-100 hover:text-zinc-700 transition"
                            >
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                            <p class="mt-1 text-sm text-zinc-500">{{ module.description }}</p>
                    </div>

                    <!-- Contenido del Panel -->
                    <div class="flex-1 overflow-y-auto p-6">
                                <div class="space-y-1">
                                    <Link
                                    v-for="item in module.items"
                                    :key="item.id"
                                    @click="closeAllModules()"
                                    :href="item.route"
                                        :class="[
                                        isItemActive(item)
                                                ? 'bg-[#7c40ff]/10 text-[#7c40ff] font-medium'
                                                : 'text-zinc-700 hover:bg-zinc-50',
                                            'flex items-center gap-3 rounded-lg px-4 py-3 transition'
                                        ]"
                                    >
                                    <svg class="h-5 w-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="item.icon" />
                                    </svg>
                                    <span class="text-sm">{{ item.name }}</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
        </template>

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
