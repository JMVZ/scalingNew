<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const page = usePage();
const showSuccess = ref(false);
const showError = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

watch(() => page.props.flash, (flash) => {
    if (flash?.success) {
        successMessage.value = flash.success;
        showSuccess.value = true;
        setTimeout(() => {
            showSuccess.value = false;
        }, 5000);
    }
    if (flash?.error) {
        errorMessage.value = flash.error;
        showError.value = true;
        setTimeout(() => {
            showError.value = false;
        }, 5000);
    }
}, { deep: true, immediate: true });
</script>

<template>
    <!-- Mensaje de Éxito -->
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="showSuccess"
            class="fixed right-4 top-4 z-50 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-6 py-4 shadow-lg"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <p class="font-medium text-green-800">{{ successMessage }}</p>
            <button
                @click="showSuccess = false"
                class="ml-4 text-green-600 hover:text-green-800"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>

    <!-- Mensaje de Error -->
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="showError"
            class="fixed right-4 top-4 z-50 flex items-center gap-3 rounded-xl border border-red-200 bg-red-50 px-6 py-4 shadow-lg"
        >
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <p class="font-medium text-red-800">{{ errorMessage }}</p>
            <button
                @click="showError = false"
                class="ml-4 text-red-600 hover:text-red-800"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>
</template>
