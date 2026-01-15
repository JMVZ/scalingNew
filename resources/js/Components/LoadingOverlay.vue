<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const page = usePage();
const isLoading = ref(false);

// Detectar cuando Inertia está procesando
watch(
    () => page.props,
    () => {
        // Se activa cuando hay una navegación en proceso
    },
    { deep: true }
);

// Escuchar eventos de Inertia
if (typeof window !== "undefined") {
    document.addEventListener("inertia:start", () => {
        isLoading.value = true;
    });

    document.addEventListener("inertia:finish", () => {
        // Pequeño delay para que se vea la animación
        setTimeout(() => {
            isLoading.value = false;
        }, 300);
    });
}
</script>

<template>
    <!-- Loading Overlay -->
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isLoading"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/20 backdrop-blur-sm"
        >
            <div class="rounded-2xl bg-white p-8 shadow-2xl">
                <div class="flex flex-col items-center gap-4">
                    <!-- Logo Animado -->
                    <div
                        class="relative flex h-24 w-24 items-center justify-center"
                    >
                        <!-- Logo -->
                        <div
                            class="absolute inset-0 flex items-center justify-center"
                        >
                            <img
                                src="/logo_solo.png"
                                alt="Loading"
                                class="h-16 w-16 object-contain animate-pulse"
                            />
                        </div>

                        <!-- Spinner alrededor del logo -->
                        <svg
                            class="h-24 w-24 animate-spin"
                            viewBox="0 0 100 100"
                        >
                            <circle
                                cx="50"
                                cy="50"
                                r="45"
                                fill="none"
                                stroke="url(#gradient)"
                                stroke-width="6"
                                stroke-linecap="round"
                                stroke-dasharray="70 200"
                            />
                            <defs>
                                <linearGradient
                                    id="gradient"
                                    x1="0%"
                                    y1="0%"
                                    x2="100%"
                                    y2="100%"
                                >
                                    <stop
                                        offset="0%"
                                        style="
                                            stop-color: #7c40ff;
                                            stop-opacity: 1;
                                        "
                                    />
                                    <stop
                                        offset="100%"
                                        style="
                                            stop-color: #b436ff;
                                            stop-opacity: 1;
                                        "
                                    />
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>

                    <!-- Texto -->
                    <div class="text-center">
                        <p class="text-lg font-semibold text-zinc-900">
                            Procesando...
                        </p>
                        <p class="text-sm text-zinc-500">
                            Por favor espera un momento
                        </p>
                    </div>

                    <!-- Barra de progreso animada -->
                    <div
                        class="w-48 h-1 bg-zinc-200 rounded-full overflow-hidden"
                    >
                        <div
                            class="h-full bg-gradient-to-r from-[#7c40ff] to-[#b436ff] animate-[pulse_1s_ease-in-out_infinite]"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
