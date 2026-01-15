<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    numero_operador: "",
    activo: true,
    fecha_contratacion: "",
    apellido_paterno: "",
    apellido_materno: "",
    nombres: "",
    telefono: "",
    correo: "",
    licencia: "",
    vencimiento_licencia: "",
});

const submit = () => {
    form.post(route("operadores.store"), {
        onSuccess: () => {
            // Redirige automáticamente
        },
    });
};
</script>

<template>
    <Head title="Crear Operador" />

    <AppLayout>
        <template #header>Crear Operador</template>

        <div class="mb-6">
            <Link
                :href="route('operadores.index')"
                class="inline-flex items-center gap-2 text-sm text-zinc-600 hover:text-zinc-900"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Volver a Operadores
            </Link>
        </div>

        <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow">
            <h2 class="mb-6 text-2xl font-bold text-zinc-900">Nuevo Operador</h2>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Datos Generales -->
                <div class="border-b border-zinc-200 pb-4">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Datos Generales</h3>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Número de Operador -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Número de Operador <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.numero_operador"
                            type="text"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="Ej: OP-001"
                        />
                        <p v-if="form.errors.numero_operador" class="mt-1 text-sm text-red-600">
                            {{ form.errors.numero_operador }}
                        </p>
                    </div>

                    <!-- Fecha de Contratación -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Fecha de Contratación
                        </label>
                        <input
                            v-model="form.fecha_contratacion"
                            type="date"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.fecha_contratacion" class="mt-1 text-sm text-red-600">
                            {{ form.errors.fecha_contratacion }}
                        </p>
                    </div>
                </div>

                <!-- Nombre Completo -->
                <div class="border-t border-zinc-200 pt-4">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Nombre Completo</h3>
                </div>

                <div class="grid gap-6 md:grid-cols-3">
                    <!-- Apellido Paterno -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.apellido_paterno"
                            type="text"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="Pérez"
                        />
                        <p v-if="form.errors.apellido_paterno" class="mt-1 text-sm text-red-600">
                            {{ form.errors.apellido_paterno }}
                        </p>
                    </div>

                    <!-- Apellido Materno -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Apellido Materno
                        </label>
                        <input
                            v-model="form.apellido_materno"
                            type="text"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="López"
                        />
                        <p v-if="form.errors.apellido_materno" class="mt-1 text-sm text-red-600">
                            {{ form.errors.apellido_materno }}
                        </p>
                    </div>

                    <!-- Nombres -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Nombres <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.nombres"
                            type="text"
                            required
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="Juan"
                        />
                        <p v-if="form.errors.nombres" class="mt-1 text-sm text-red-600">
                            {{ form.errors.nombres }}
                        </p>
                    </div>
                </div>

                <!-- Contacto -->
                <div class="border-t border-zinc-200 pt-4">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Contacto</h3>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Teléfono
                        </label>
                        <input
                            v-model="form.telefono"
                            type="tel"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="5551234567"
                        />
                        <p v-if="form.errors.telefono" class="mt-1 text-sm text-red-600">
                            {{ form.errors.telefono }}
                        </p>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Correo
                        </label>
                        <input
                            v-model="form.correo"
                            type="email"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="operador@email.com"
                        />
                        <p v-if="form.errors.correo" class="mt-1 text-sm text-red-600">
                            {{ form.errors.correo }}
                        </p>
                    </div>
                </div>

                <!-- Licencia -->
                <div class="border-t border-zinc-200 pt-4">
                    <h3 class="mb-4 text-lg font-semibold text-zinc-900">Licencia</h3>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <!-- Número de Licencia -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Número de Licencia
                        </label>
                        <input
                            v-model="form.licencia"
                            type="text"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                            placeholder="A1234567"
                        />
                        <p v-if="form.errors.licencia" class="mt-1 text-sm text-red-600">
                            {{ form.errors.licencia }}
                        </p>
                    </div>

                    <!-- Vencimiento de Licencia -->
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">
                            Vencimiento de Licencia
                        </label>
                        <input
                            v-model="form.vencimiento_licencia"
                            type="date"
                            class="w-full rounded-lg border border-zinc-300 px-4 py-2 focus:border-[#7c40ff] focus:outline-none focus:ring-2 focus:ring-[#7c40ff]/20"
                        />
                        <p v-if="form.errors.vencimiento_licencia" class="mt-1 text-sm text-red-600">
                            {{ form.errors.vencimiento_licencia }}
                        </p>
                    </div>
                </div>

                <!-- Activo -->
                <div class="flex items-center gap-2">
                    <input
                        v-model="form.activo"
                        type="checkbox"
                        id="activo"
                        class="h-4 w-4 rounded border-zinc-300 text-[#7c40ff] focus:ring-[#7c40ff]"
                    />
                    <label for="activo" class="text-sm text-zinc-700">
                        Operador activo
                    </label>
                </div>

                <!-- Botones -->
                <div class="flex items-center gap-4 pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#7c40ff] to-[#b436ff] px-6 py-3 font-semibold text-white shadow-lg transition hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-5 w-5 animate-spin"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg
                            v-else
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ form.processing ? "Creando..." : "Crear Operador" }}
                    </button>

                    <Link
                        :href="route('operadores.index')"
                        class="rounded-xl border border-zinc-300 px-6 py-3 font-semibold text-zinc-700 transition hover:bg-zinc-50"
                    >
                        Cancelar
                    </Link>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
