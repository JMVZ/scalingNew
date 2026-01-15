<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import {
    Chart,
    LineController,
    BarController,
    DoughnutController,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';

// Registrar componentes de Chart.js
Chart.register(
    LineController,
    BarController,
    DoughnutController,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend
);

const props = defineProps({
    kpis: Object,
    viajes_por_mes: Object,
    facturacion_mensual: Object,
    ingresos_vs_egresos: Object,
    estatus_flota: Object,
    viajes_por_estatus: Object,
    top_rutas: Array,
    top_operadores: Array,
    gastos_por_categoria: Object,
    cuentas_por_cobrar: Object,
    cuentas_por_pagar: Object,
    flujo_efectivo: Object,
    rentabilidad_viaje: Object,
    cumplimiento_fiscal: Object,
    costos_por_unidad: Array,
});

const viajesChart = ref(null);
const facturacionChart = ref(null);
const flotaChart = ref(null);
const gastosChart = ref(null);

const formatCurrency = (value) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(value);
};

const calcularPorcentajeCambio = (actual, anterior) => {
    const cambio = ((actual - anterior) / anterior) * 100;
    return cambio.toFixed(1);
};

onMounted(() => {
    // Gráfico de Viajes por Mes
    if (viajesChart.value) {
        new Chart(viajesChart.value, {
            type: 'line',
            data: {
                labels: props.viajes_por_mes.labels,
                datasets: [{
                    label: 'Viajes Realizados',
                    data: props.viajes_por_mes.data,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { font: { size: 12 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 12 } }
                    }
                }
            }
        });
    }

    // Gráfico de Facturación Mensual
    if (facturacionChart.value) {
        new Chart(facturacionChart.value, {
            type: 'bar',
            data: {
                labels: props.facturacion_mensual.labels,
                datasets: [
                    {
                        label: 'Ingresos',
                        data: props.facturacion_mensual.ingresos,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    },
                    {
                        label: 'Gastos',
                        data: props.facturacion_mensual.gastos,
                        backgroundColor: 'rgba(239, 68, 68, 0.8)',
                        borderRadius: 8,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: { font: { size: 13, weight: 'bold' } }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.parsed.y.toLocaleString('es-MX');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: {
                            callback: function(value) {
                                return '$' + (value / 1000000).toFixed(1) + 'M';
                            }
                        }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Gráfico de Estatus de Flota
    if (flotaChart.value) {
        new Chart(flotaChart.value, {
            type: 'doughnut',
            data: {
                labels: props.estatus_flota.labels,
                datasets: [{
                    data: props.estatus_flota.data,
                    backgroundColor: ['#8b5cf6', '#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { 
                            padding: 15,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                    }
                }
            }
        });
    }

    // Gráfico de Gastos por Categoría
    if (gastosChart.value) {
        new Chart(gastosChart.value, {
            type: 'doughnut',
            data: {
                labels: props.gastos_por_categoria.labels,
                datasets: [{
                    data: props.gastos_por_categoria.data,
                    backgroundColor: ['#ef4444', '#f59e0b', '#8b5cf6', '#06b6d4', '#64748b'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 10,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: { 
                            padding: 15,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.label + ': $' + context.parsed.toLocaleString('es-MX');
                            }
                        }
                    }
                }
            }
        });
    }
});
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
                    <p class="text-sm text-zinc-500">Panel de control y métricas operativas</p>
                </div>
            </div>
        </template>

        <!-- KPIs Principales con Gradientes -->
        <div class="mb-8 grid gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Viajes del Mes -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-purple-500 to-purple-700 p-6 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 transform rounded-full bg-white opacity-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-purple-100">Viajes del Mes</p>
                            <p class="mt-3 text-4xl font-extrabold text-white">{{ kpis.viajes_mes }}</p>
                            <div class="mt-3 flex items-center gap-1.5">
                                <span
                                    :class="[
                                        kpis.viajes_mes > kpis.viajes_mes_anterior ? 'bg-green-400' : 'bg-red-400',
                                        'rounded-full px-2 py-0.5 text-xs font-bold text-white'
                                    ]"
                                >
                                    {{ kpis.viajes_mes > kpis.viajes_mes_anterior ? '↑' : '↓' }}
                                    {{ calcularPorcentajeCambio(kpis.viajes_mes, kpis.viajes_mes_anterior) }}%
                                </span>
                                <span class="text-xs text-purple-100">vs anterior</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-white bg-opacity-20 p-4 backdrop-blur-sm">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facturación Mensual -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-500 to-emerald-700 p-6 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 transform rounded-full bg-white opacity-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-100">Facturación</p>
                            <p class="mt-3 text-4xl font-extrabold text-white">{{ formatCurrency(kpis.facturacion_mes).slice(0, -3) }}</p>
                            <div class="mt-3 flex items-center gap-1.5">
                                <span
                                    :class="[
                                        kpis.facturacion_mes > kpis.facturacion_mes_anterior ? 'bg-emerald-300' : 'bg-red-400',
                                        'rounded-full px-2 py-0.5 text-xs font-bold text-emerald-900'
                                    ]"
                                >
                                    {{ kpis.facturacion_mes > kpis.facturacion_mes_anterior ? '↑' : '↓' }}
                                    {{ calcularPorcentajeCambio(kpis.facturacion_mes, kpis.facturacion_mes_anterior) }}%
                                </span>
                                <span class="text-xs text-green-100">vs anterior</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-white bg-opacity-20 p-4 backdrop-blur-sm">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cobranza Efectiva -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 p-6 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 transform rounded-full bg-white opacity-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-blue-100">Cobranza</p>
                            <p class="mt-3 text-4xl font-extrabold text-white">{{ kpis.cobranza_efectiva }}%</p>
                            <div class="mt-3 flex items-center gap-1.5">
                                <span
                                    :class="[
                                        kpis.cobranza_efectiva > kpis.cobranza_anterior ? 'bg-blue-300' : 'bg-red-400',
                                        'rounded-full px-2 py-0.5 text-xs font-bold text-blue-900'
                                    ]"
                                >
                                    {{ kpis.cobranza_efectiva > kpis.cobranza_anterior ? '↑' : '↓' }}
                                    {{ (kpis.cobranza_efectiva - kpis.cobranza_anterior).toFixed(1) }}pts
                                </span>
                                <span class="text-xs text-blue-100">vs anterior</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-white bg-opacity-20 p-4 backdrop-blur-sm">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Utilidad del Mes -->
            <div class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 p-6 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <div class="absolute right-0 top-0 h-32 w-32 translate-x-8 -translate-y-8 transform rounded-full bg-white opacity-10"></div>
                <div class="relative">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-orange-100">Utilidad</p>
                            <p class="mt-3 text-4xl font-extrabold text-white">{{ formatCurrency(kpis.utilidad_mes).slice(0, -3) }}</p>
                            <div class="mt-3 flex items-center gap-1.5">
                                <span
                                    :class="[
                                        kpis.utilidad_mes > kpis.utilidad_anterior ? 'bg-orange-300' : 'bg-red-300',
                                        'rounded-full px-2 py-0.5 text-xs font-bold text-orange-900'
                                    ]"
                                >
                                    {{ kpis.utilidad_mes > kpis.utilidad_anterior ? '↑' : '↓' }}
                                    {{ calcularPorcentajeCambio(kpis.utilidad_mes, kpis.utilidad_anterior) }}%
                                </span>
                                <span class="text-xs text-orange-100">vs anterior</span>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-white bg-opacity-20 p-4 backdrop-blur-sm">
                            <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos Principales -->
        <div class="mb-8 grid gap-6 lg:grid-cols-2">
            <!-- Viajes por Mes -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-purple-50 to-white p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-100 p-2">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Viajes por Mes</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas ref="viajesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Facturación: Ingresos vs Gastos -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-green-50 to-white p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-green-100 p-2">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Ingresos vs Gastos</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="h-64">
                        <canvas ref="facturacionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda Fila de Gráficos -->
        <div class="mb-8 grid gap-6 lg:grid-cols-2">
            <!-- Estatus de Flota -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-purple-50 to-white p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-100 p-2">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Estatus de Flota</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex h-64 items-center justify-center">
                        <canvas ref="flotaChart"></canvas>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 p-4 text-center">
                            <p class="text-3xl font-extrabold text-purple-700">{{ kpis.unidades_activas }}</p>
                            <p class="mt-1 text-xs font-semibold text-purple-600">Activas</p>
                        </div>
                        <div class="rounded-xl bg-gradient-to-br from-zinc-50 to-zinc-100 p-4 text-center">
                            <p class="text-3xl font-extrabold text-zinc-700">{{ kpis.unidades_totales }}</p>
                            <p class="mt-1 text-xs font-semibold text-zinc-600">Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gastos por Categoría -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-red-50 to-white p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-red-100 p-2">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Gastos por Categoría</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex h-64 items-center justify-center">
                        <canvas ref="gastosChart"></canvas>
                    </div>
                    <div class="mt-6 text-center">
                        <p class="text-3xl font-extrabold text-zinc-900">{{ formatCurrency(kpis.gastos_operativos) }}</p>
                        <p class="mt-1 text-sm font-semibold text-zinc-500">Total Gastos del Mes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Viajes Activos por Estatus -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
            <div class="border-b border-zinc-100 bg-gradient-to-r from-blue-50 to-white p-6">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-blue-100 p-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900">Viajes Activos por Estatus</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="grid gap-4 md:grid-cols-4">
                    <div class="group rounded-xl border-2 border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-5 text-center transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <p class="text-4xl font-extrabold text-blue-600">{{ viajes_por_estatus.planeacion }}</p>
                        <p class="mt-2 text-sm font-bold text-blue-700">En Planeación</p>
                    </div>
                    <div class="group rounded-xl border-2 border-purple-200 bg-gradient-to-br from-purple-50 to-purple-100 p-5 text-center transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <p class="text-4xl font-extrabold text-purple-600">{{ viajes_por_estatus.en_ejecucion }}</p>
                        <p class="mt-2 text-sm font-bold text-purple-700">En Ejecución</p>
                    </div>
                    <div class="group rounded-xl border-2 border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-5 text-center transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <p class="text-4xl font-extrabold text-green-600">{{ viajes_por_estatus.completados }}</p>
                        <p class="mt-2 text-sm font-bold text-green-700">Completados</p>
                    </div>
                    <div class="group rounded-xl border-2 border-red-200 bg-gradient-to-br from-red-50 to-red-100 p-5 text-center transition-all duration-300 hover:scale-105 hover:shadow-lg">
                        <p class="text-4xl font-extrabold text-red-600">{{ viajes_por_estatus.cancelados }}</p>
                        <p class="mt-2 text-sm font-bold text-red-700">Cancelados</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicadores Financieros -->
        <div class="mb-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <!-- Cuentas por Cobrar -->
            <div class="overflow-hidden rounded-2xl border border-orange-200 bg-gradient-to-br from-orange-50 to-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="border-b border-orange-100 bg-gradient-to-r from-orange-100 to-orange-50 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-orange-500 p-3 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-orange-900">Cuentas por Cobrar</h3>
                            <p class="text-xs text-orange-600">Estado de cobranza</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-zinc-600">Total</span>
                            <span class="text-lg font-extrabold text-zinc-900">{{ formatCurrency(cuentas_por_cobrar.total) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-red-50 p-3">
                            <span class="text-sm font-semibold text-red-700">Vencidas</span>
                            <span class="text-lg font-extrabold text-red-600">{{ formatCurrency(cuentas_por_cobrar.vencidas) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-green-50 p-3">
                            <span class="text-sm font-semibold text-green-700">Por Vencer</span>
                            <span class="text-lg font-extrabold text-green-600">{{ formatCurrency(cuentas_por_cobrar.por_vencer) }}</span>
                        </div>
                        <div class="mt-4 rounded-lg border-t-2 border-orange-200 pt-3 text-center">
                            <p class="text-xs font-semibold text-orange-600">{{ cuentas_por_cobrar.clientes_con_saldo }} clientes con saldo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cuentas por Pagar -->
            <div class="overflow-hidden rounded-2xl border border-red-200 bg-gradient-to-br from-red-50 to-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="border-b border-red-100 bg-gradient-to-r from-red-100 to-red-50 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-red-500 p-3 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-red-900">Cuentas por Pagar</h3>
                            <p class="text-xs text-red-600">Obligaciones pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-zinc-600">Total</span>
                            <span class="text-lg font-extrabold text-zinc-900">{{ formatCurrency(cuentas_por_pagar.total) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-red-100 p-3">
                            <span class="text-sm font-semibold text-red-800">Vencidas</span>
                            <span class="text-lg font-extrabold text-red-700">{{ formatCurrency(cuentas_por_pagar.vencidas) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-green-50 p-3">
                            <span class="text-sm font-semibold text-green-700">Por Vencer</span>
                            <span class="text-lg font-extrabold text-green-600">{{ formatCurrency(cuentas_por_pagar.por_vencer) }}</span>
                        </div>
                        <div class="mt-4 rounded-lg border-t-2 border-red-200 pt-3 text-center">
                            <p class="text-xs font-semibold text-red-600">{{ cuentas_por_pagar.proveedores_con_saldo }} proveedores con saldo</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flujo de Efectivo -->
            <div class="overflow-hidden rounded-2xl border border-purple-200 bg-gradient-to-br from-purple-50 to-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="border-b border-purple-100 bg-gradient-to-r from-purple-100 to-purple-50 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-purple-500 p-3 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-purple-900">Flujo de Efectivo</h3>
                            <p class="text-xs text-purple-600">Movimientos del mes</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-zinc-600">Saldo Inicial</span>
                            <span class="text-lg font-bold text-zinc-900">{{ formatCurrency(flujo_efectivo.saldo_inicial) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-green-50 p-3">
                            <span class="text-sm font-semibold text-green-700">+ Ingresos</span>
                            <span class="text-lg font-extrabold text-green-600">{{ formatCurrency(flujo_efectivo.ingresos_mes) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-red-50 p-3">
                            <span class="text-sm font-semibold text-red-700">- Egresos</span>
                            <span class="text-lg font-extrabold text-red-600">{{ formatCurrency(flujo_efectivo.egresos_mes) }}</span>
                        </div>
                        <div class="mt-4 rounded-xl border-2 border-purple-500 bg-gradient-to-r from-purple-100 to-purple-200 p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-purple-900">Saldo Final</span>
                                <span class="text-2xl font-extrabold text-purple-700">{{ formatCurrency(flujo_efectivo.saldo_final) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Rutas y Operadores -->
        <div class="mb-8 grid gap-6 lg:grid-cols-2">
            <!-- Top 5 Rutas -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-purple-50 to-white p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-100 p-2">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Top 5 Rutas Rentables</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div
                            v-for="(ruta, index) in top_rutas"
                            :key="index"
                            class="group flex items-center justify-between rounded-xl border-2 border-zinc-100 bg-gradient-to-r from-zinc-50 to-white p-4 transition-all duration-300 hover:scale-105 hover:border-purple-200 hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-purple-400 to-purple-600 text-sm font-extrabold text-white shadow-lg">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-900">{{ ruta.nombre }}</p>
                                    <p class="text-xs font-semibold text-purple-600">{{ ruta.viajes }} viajes realizados</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-extrabold text-green-600">{{ formatCurrency(ruta.ingresos) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 5 Operadores -->
            <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="border-b border-zinc-100 bg-gradient-to-r from-blue-50 to-white p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-blue-100 p-2">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">Top 5 Operadores</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div
                            v-for="(operador, index) in top_operadores"
                            :key="index"
                            class="group flex items-center justify-between rounded-xl border-2 border-zinc-100 bg-gradient-to-r from-zinc-50 to-white p-4 transition-all duration-300 hover:scale-105 hover:border-blue-200 hover:shadow-md"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-sm font-extrabold text-white shadow-lg">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <p class="font-bold text-zinc-900">{{ operador.nombre }}</p>
                                    <p class="text-xs font-semibold text-blue-600">{{ operador.viajes }} viajes completados</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 rounded-full bg-gradient-to-r from-yellow-100 to-yellow-200 px-3 py-1.5">
                                <svg class="h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                <span class="text-sm font-extrabold text-yellow-700">{{ operador.calificacion }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Costos por Unidad -->
        <div class="mb-8 overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-lg">
            <div class="border-b border-zinc-100 bg-gradient-to-r from-orange-50 to-white p-6">
                <div class="flex items-center gap-3">
                    <div class="rounded-lg bg-orange-100 p-2">
                        <svg class="h-5 w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                    </div>
                    <h3 class="text-lg font-bold text-zinc-900">Costos por Unidad (Top 5)</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-zinc-200 bg-gradient-to-r from-zinc-50 to-zinc-100">
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase text-zinc-700">Unidad</th>
                                <th class="px-4 py-4 text-right text-xs font-bold uppercase text-zinc-700">Combustible</th>
                                <th class="px-4 py-4 text-right text-xs font-bold uppercase text-zinc-700">Mantenimiento</th>
                                <th class="px-4 py-4 text-right text-xs font-bold uppercase text-zinc-700">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(unidad, index) in costos_por_unidad"
                                :key="index"
                                class="border-b border-zinc-100 transition-all duration-200 hover:bg-orange-50"
                            >
                                <td class="px-4 py-4 font-bold text-zinc-900">{{ unidad.unidad }}</td>
                                <td class="px-4 py-4 text-right font-semibold text-zinc-600">{{ formatCurrency(unidad.combustible) }}</td>
                                <td class="px-4 py-4 text-right font-semibold text-zinc-600">{{ formatCurrency(unidad.mantenimiento) }}</td>
                                <td class="px-4 py-4 text-right text-lg font-extrabold text-orange-600">{{ formatCurrency(unidad.total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Información Final -->
        <div class="grid gap-6 md:grid-cols-2">
            <!-- Rentabilidad por Viaje -->
            <div class="overflow-hidden rounded-2xl border border-purple-200 bg-gradient-to-br from-purple-50 to-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="border-b border-purple-100 bg-gradient-to-r from-purple-100 to-purple-50 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-purple-500 p-3 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-purple-900">Rentabilidad por Viaje</h3>
                            <p class="text-xs text-purple-600">Promedio general</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between rounded-lg bg-green-50 p-3">
                            <span class="text-sm font-semibold text-green-700">Ingreso</span>
                            <span class="text-lg font-extrabold text-green-600">{{ formatCurrency(rentabilidad_viaje.ingreso_promedio) }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-red-50 p-3">
                            <span class="text-sm font-semibold text-red-700">Costo</span>
                            <span class="text-lg font-extrabold text-red-600">{{ formatCurrency(rentabilidad_viaje.costo_promedio) }}</span>
                        </div>
                        <div class="mt-6 rounded-xl border-2 border-purple-500 bg-gradient-to-r from-purple-100 to-purple-200 p-5 text-center">
                            <p class="text-sm font-bold text-purple-800">Utilidad Promedio</p>
                            <p class="mt-2 text-3xl font-extrabold text-purple-700">{{ formatCurrency(rentabilidad_viaje.utilidad_promedio) }}</p>
                            <div class="mt-3">
                                <span class="rounded-full bg-purple-600 px-4 py-2 text-sm font-extrabold text-white shadow-lg">
                                    Margen: {{ rentabilidad_viaje.margen_promedio }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cumplimiento Fiscal -->
            <div class="overflow-hidden rounded-2xl border border-green-200 bg-gradient-to-br from-green-50 to-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="border-b border-green-100 bg-gradient-to-r from-green-100 to-green-50 p-6">
                    <div class="flex items-center gap-3">
                        <div class="rounded-xl bg-green-500 p-3 shadow-lg">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-green-900">Cumplimiento Fiscal</h3>
                            <p class="text-xs text-green-600">Estado de facturación</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-zinc-600">Emitidas</span>
                            <span class="text-lg font-bold text-zinc-900">{{ cumplimiento_fiscal.facturas_emitidas }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-blue-50 p-3">
                            <span class="text-sm font-semibold text-blue-700">Timbradas</span>
                            <span class="text-lg font-extrabold text-blue-600">{{ cumplimiento_fiscal.facturas_timbradas }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-lg bg-green-100 p-3">
                            <span class="text-sm font-semibold text-green-800">Pagadas</span>
                            <span class="text-lg font-extrabold text-green-700">{{ cumplimiento_fiscal.facturas_pagadas }}</span>
                        </div>
                        <div class="mt-6 rounded-xl border-2 border-green-500 bg-gradient-to-r from-green-100 to-green-200 p-5 text-center">
                            <p class="text-sm font-bold text-green-800">Cumplimiento</p>
                            <p class="mt-2 text-4xl font-extrabold text-green-700">{{ cumplimiento_fiscal.cumplimiento }}%</p>
                            <div class="mt-3 h-3 w-full overflow-hidden rounded-full bg-green-200">
                                <div class="h-full rounded-full bg-gradient-to-r from-green-500 to-green-600 transition-all duration-500" :style="{ width: cumplimiento_fiscal.cumplimiento + '%' }"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
