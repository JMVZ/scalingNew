<script setup>
import { ref } from 'vue';

const isOpen = ref(false);
const title = ref('');
const message = ref('');
const confirmCallback = ref(null);

const show = (options) => {
    title.value = options.title || '¿Estás seguro?';
    message.value = options.message || '';
    confirmCallback.value = options.onConfirm;
    isOpen.value = true;
};

const confirm = () => {
    if (confirmCallback.value) {
        confirmCallback.value();
    }
    isOpen.value = false;
};

const cancel = () => {
    isOpen.value = false;
};

defineExpose({ show });
</script>

<template>
    <!-- Overlay -->
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isOpen"
            class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm"
            @click="cancel"
        ></div>
    </Transition>

    <!-- Modal -->
    <Transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 scale-95"
        enter-to-class="opacity-100 scale-100"
        leave-active-class="transition duration-150 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
    >
        <div
            v-if="isOpen"
            class="fixed left-1/2 top-1/2 z-50 w-full max-w-md -translate-x-1/2 -translate-y-1/2 rounded-2xl border border-zinc-200 bg-white p-6 shadow-2xl"
        >
            <!-- Icono de Advertencia -->
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Título -->
            <h3 class="mb-2 text-center text-xl font-bold text-zinc-900">
                {{ title }}
            </h3>

            <!-- Mensaje -->
            <p class="mb-6 text-center text-sm text-zinc-600">
                {{ message }}
            </p>

            <!-- Botones -->
            <div class="flex gap-3">
                <button
                    @click="cancel"
                    class="flex-1 rounded-xl border border-zinc-300 px-4 py-3 font-semibold text-zinc-700 transition hover:bg-zinc-50"
                >
                    Cancelar
                </button>
                <button
                    @click="confirm"
                    class="flex-1 rounded-xl bg-red-600 px-4 py-3 font-semibold text-white transition hover:bg-red-700"
                >
                    Eliminar
                </button>
            </div>
        </div>
    </Transition>
</template>
