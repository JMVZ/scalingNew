<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\OrdenCargaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->load('empresa');
});

// Rutas protegidas con autenticación
Route::middleware('auth:sanctum')->group(function () {
    
    // Rutas de Clientes (API)
    Route::apiResource('clientes', ClienteController::class)->names([
        'index' => 'api.clientes.index',
        'store' => 'api.clientes.store',
        'show' => 'api.clientes.show',
        'update' => 'api.clientes.update',
        'destroy' => 'api.clientes.destroy',
    ]);
    
    // Rutas de Órdenes de Carga (API)
    Route::apiResource('ordenes-carga', OrdenCargaController::class)->names([
        'index' => 'api.ordenes-carga.index',
        'store' => 'api.ordenes-carga.store',
        'show' => 'api.ordenes-carga.show',
        'update' => 'api.ordenes-carga.update',
        'destroy' => 'api.ordenes-carga.destroy',
    ]);
    
    // Ruta adicional para cambiar estado de orden
    Route::patch('ordenes-carga/{ordenCarga}/estado', [OrdenCargaController::class, 'cambiarEstado'])
        ->name('api.ordenes-carga.cambiar-estado');
    
});

