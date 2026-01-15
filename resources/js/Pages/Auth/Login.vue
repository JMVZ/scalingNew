<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import LoadingOverlay from '@/Components/LoadingOverlay.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    rfc: '',
    email: '',
    password: '',
});

const isLoading = ref(false);
const error = ref('');

const submit = () => {
    isLoading.value = true;
    error.value = '';
    
    if (!form.rfc) {
        error.value = 'El RFC de la empresa es obligatorio';
        isLoading.value = false;
        return;
    }
    
    if (!form.email) {
        error.value = 'El correo electrónico es obligatorio';
        isLoading.value = false;
        return;
    }
    
    if (!form.password) {
        error.value = 'La contraseña es obligatoria';
        isLoading.value = false;
        return;
    }
    
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
            isLoading.value = false;
        },
        onError: (errors) => {
            error.value = errors.rfc || errors.email || errors.password || 'Error al iniciar sesión';
            isLoading.value = false;
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Iniciar Sesión" />
        
        <!-- Loading Overlay -->
        <LoadingOverlay />

        <div class="relative min-h-screen overflow-hidden bg-[#f6f7fb] text-zinc-900">
            <!-- Video Background -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <video 
                    autoplay 
                    loop 
                    muted 
                    playsinline
                    src="/background.mp4"
                    class="absolute bottom-0 left-0 right-0 h-[70%] w-full object-cover"
                    style="clip-path: polygon(0 75%, 100% 12%, 100% 100%, 0 100%);"
                />
                
                <!-- Overlay sutil para mejorar legibilidad -->
                <div 
                    class="absolute bottom-0 left-0 right-0 h-[70%]"
                    style="background: linear-gradient(135deg, rgba(124, 64, 255, 0.1) 0%, rgba(180, 54, 255, 0.1) 100%); clip-path: polygon(0 75%, 100% 12%, 100% 100%, 0 100%);"
                />
            </div>

            <div class="relative z-10 flex min-h-screen flex-col items-center px-6 pb-12 pt-8">
                <header class="flex w-full max-w-6xl flex-col gap-5 text-sm text-zinc-600 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <img
                            src="/SCALING IT - LOGO - WEBP_600px (1).webp"
                            alt="Scaling"
                            class="h-14 w-auto object-contain"
                        />
                    </div>
                </header>

                <main class="mt-16 flex w-full max-w-4xl flex-col items-center gap-6 text-center">
                    <div class="relative w-full max-w-[500px]">
                        <div class="relative w-full rounded-[36px] border border-white/80 bg-white/95 p-10 text-left shadow-[0_25px_80px_rgba(86,64,205,0.18)] backdrop-blur">
                            <div class="mb-8 space-y-2 text-center">
                                <p class="text-3xl font-semibold text-zinc-900">
                                    ¡Te damos la bienvenida de nuevo!
                                </p>
                                <p class="text-sm text-zinc-500">
                                    Accede a tu espacio de trabajo en segundos.
                                </p>
                            </div>

                            <div v-if="error || form.errors.email || form.errors.password" class="mb-4 rounded-2xl bg-red-50 border border-red-200 p-3 text-sm text-red-600">
                                {{ error || form.errors.email || form.errors.password }}
                            </div>

                            <div v-if="status" class="mb-4 rounded-2xl bg-green-50 border border-green-200 p-3 text-sm text-green-600">
                                {{ status }}
                            </div>

                            <form @submit.prevent="submit" class="space-y-5">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-zinc-600">
                                        RFC de la Empresa <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-3 rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-3 shadow-inner transition focus-within:border-[#7c40ff] focus-within:bg-white focus-within:shadow-[0_0_0_3px_rgba(124,64,255,0.12)]">
                                        <svg class="size-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <input
                                            type="text"
                                            v-model="form.rfc"
                                            placeholder="RFC de tu empresa (ej: TDE010101AAA)"
                                            required
                                            maxlength="13"
                                            class="w-full border-none bg-transparent text-sm text-zinc-700 placeholder:text-zinc-400 focus:outline-none uppercase"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-zinc-600">
                                        Correo Electrónico <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center gap-3 rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-3 shadow-inner transition focus-within:border-[#7c40ff] focus-within:bg-white focus-within:shadow-[0_0_0_3px_rgba(124,64,255,0.12)]">
                                        <svg class="size-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                        <input
                                            type="email"
                                            v-model="form.email"
                                            placeholder="tu@email.com"
                                            required
                                            class="w-full border-none bg-transparent text-sm text-zinc-700 placeholder:text-zinc-400 focus:outline-none"
                                        />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-sm font-medium text-zinc-600">
                                        <span>Contraseña</span>
                                        <Link
                                            v-if="canResetPassword"
                                            :href="route('password.request')"
                                            class="text-[#7c40ff] transition hover:text-[#a238ff]"
                                        >
                                            ¿Olvidaste la contraseña?
                                        </Link>
                                    </div>
                                    <div class="flex items-center gap-3 rounded-2xl border border-zinc-200 bg-zinc-50/80 px-4 py-3 shadow-inner transition focus-within:border-[#7c40ff] focus-within:bg-white focus-within:shadow-[0_0_0_3px_rgba(124,64,255,0.12)]">
                                        <svg class="size-5 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        <input
                                            type="password"
                                            v-model="form.password"
                                            placeholder="Escribe la contraseña"
                                            required
                                            class="w-full border-none bg-transparent text-sm text-zinc-700 placeholder:text-zinc-400 focus:outline-none"
                                        />
                                    </div>
                                </div>

                                <button 
                                    type="submit"
                                    :disabled="isLoading || form.processing"
                                    class="mt-2 h-12 w-full rounded-2xl bg-[#5d3bff] text-base font-semibold tracking-wide text-white shadow-[0_18px_35px_rgba(94,59,255,0.35)] transition hover:translate-y-0 hover:scale-[1.005] disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    {{ isLoading || form.processing ? 'Iniciando sesión...' : 'Iniciar sesión' }}
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4 rounded-2xl bg-white/95 px-6 py-3 shadow-[0_10px_30px_rgba(0,0,0,0.1)] backdrop-blur-sm">
                        <p class="text-sm text-zinc-600">
                            ¿No puedes acceder? Contacta con tu administrador.
                        </p>
                    </div>
                </main>
            </div>

            <button class="fixed bottom-6 right-6 z-20 flex items-center gap-2 rounded-2xl border border-white/60 bg-white px-4 py-2 text-sm font-medium text-[#a238ff] shadow-[0_15px_35px_rgba(118,75,255,0.25)] transition hover:-translate-y-0.5">
                <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Ayuda
            </button>
        </div>
    </GuestLayout>
</template>
