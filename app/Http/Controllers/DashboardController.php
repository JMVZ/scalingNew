<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        // Datos de demostración para el dashboard
        $dashboardData = [
            // Indicadores principales (KPIs)
            'kpis' => [
                'viajes_mes' => 147,
                'viajes_mes_anterior' => 132,
                'facturacion_mes' => 2850000.00,
                'facturacion_mes_anterior' => 2620000.00,
                'cobranza_efectiva' => 92.5, // Porcentaje
                'cobranza_anterior' => 88.3,
                'gastos_operativos' => 1620000.00,
                'gastos_anterior' => 1580000.00,
                'utilidad_mes' => 1230000.00,
                'utilidad_anterior' => 1040000.00,
                'unidades_activas' => 45,
                'unidades_totales' => 50,
            ],
            
            // Viajes por periodo (últimos 6 meses)
            'viajes_por_mes' => [
                'labels' => ['Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                'data' => [98, 112, 125, 118, 132, 147],
            ],
            
            // Facturación mensual (últimos 6 meses)
            'facturacion_mensual' => [
                'labels' => ['Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                'ingresos' => [1850000, 2100000, 2350000, 2200000, 2620000, 2850000],
                'gastos' => [1200000, 1350000, 1450000, 1420000, 1580000, 1620000],
            ],
            
            // Ingresos vs Egresos (año actual)
            'ingresos_vs_egresos' => [
                'total_ingresos' => 28450000.00,
                'total_egresos' => 18320000.00,
                'utilidad_neta' => 10130000.00,
                'margen' => 35.6, // Porcentaje
            ],
            
            // Estatus de flota
            'estatus_flota' => [
                'labels' => ['En Viaje', 'Disponibles', 'Mantenimiento', 'Inactivas'],
                'data' => [28, 15, 5, 2],
                'colors' => ['#7c40ff', '#10b981', '#f59e0b', '#ef4444'],
            ],
            
            // Viajes por estatus
            'viajes_por_estatus' => [
                'planeacion' => 12,
                'en_ejecucion' => 28,
                'completados' => 89,
                'cancelados' => 3,
            ],
            
            // Top 5 rutas más rentables
            'top_rutas' => [
                ['nombre' => 'CDMX - Guadalajara', 'viajes' => 42, 'ingresos' => 630000],
                ['nombre' => 'Monterrey - Tijuana', 'viajes' => 38, 'ingresos' => 570000],
                ['nombre' => 'CDMX - Querétaro', 'viajes' => 35, 'ingresos' => 420000],
                ['nombre' => 'Guadalajara - León', 'viajes' => 28, 'ingresos' => 364000],
                ['nombre' => 'Puebla - Veracruz', 'viajes' => 24, 'ingresos' => 336000],
            ],
            
            // Desempeño de operadores (Top 5)
            'top_operadores' => [
                ['nombre' => 'Juan Pérez López', 'viajes' => 28, 'calificacion' => 4.8],
                ['nombre' => 'María González Ruiz', 'viajes' => 26, 'calificacion' => 4.9],
                ['nombre' => 'Carlos Rodríguez', 'viajes' => 24, 'calificacion' => 4.7],
                ['nombre' => 'Ana Martínez', 'viajes' => 22, 'calificacion' => 4.6],
                ['nombre' => 'Luis Hernández', 'viajes' => 21, 'calificacion' => 4.8],
            ],
            
            // Gastos por categoría
            'gastos_por_categoria' => [
                'labels' => ['Combustible', 'Mantenimiento', 'Salarios', 'Casetas', 'Otros'],
                'data' => [580000, 320000, 480000, 140000, 100000],
                'colors' => ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6'],
            ],
            
            // Cuentas por cobrar
            'cuentas_por_cobrar' => [
                'total' => 1250000.00,
                'vencidas' => 180000.00,
                'por_vencer' => 1070000.00,
                'clientes_con_saldo' => 18,
            ],
            
            // Cuentas por pagar
            'cuentas_por_pagar' => [
                'total' => 420000.00,
                'vencidas' => 35000.00,
                'por_vencer' => 385000.00,
                'proveedores_con_saldo' => 12,
            ],
            
            // Flujo de efectivo (últimos 30 días)
            'flujo_efectivo' => [
                'saldo_inicial' => 850000.00,
                'ingresos_mes' => 2850000.00,
                'egresos_mes' => 1620000.00,
                'saldo_final' => 2080000.00,
            ],
            
            // Rentabilidad por viaje (promedio)
            'rentabilidad_viaje' => [
                'ingreso_promedio' => 19387.75,
                'costo_promedio' => 11020.41,
                'utilidad_promedio' => 8367.34,
                'margen_promedio' => 43.2, // Porcentaje
            ],
            
            // Cumplimiento fiscal
            'cumplimiento_fiscal' => [
                'facturas_emitidas' => 147,
                'facturas_timbradas' => 145,
                'facturas_pagadas' => 136,
                'cumplimiento' => 98.6, // Porcentaje
            ],
            
            // Costos por unidad (Top 5 con más gastos)
            'costos_por_unidad' => [
                ['unidad' => 'TRK-001', 'combustible' => 45000, 'mantenimiento' => 12000, 'total' => 57000],
                ['unidad' => 'TRK-015', 'combustible' => 42000, 'mantenimiento' => 18000, 'total' => 60000],
                ['unidad' => 'TRK-008', 'combustible' => 40000, 'mantenimiento' => 8000, 'total' => 48000],
                ['unidad' => 'TRK-023', 'combustible' => 38000, 'mantenimiento' => 15000, 'total' => 53000],
                ['unidad' => 'TRK-012', 'combustible' => 37000, 'mantenimiento' => 6000, 'total' => 43000],
            ],
        ];
        
        return Inertia::render('Dashboard', $dashboardData);
    }
}
