<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\ClienteController;
use App\Http\Controllers\Web\OrdenCargaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Clientes
    Route::resource('clientes', ClienteController::class);
    
    // Rutas de Órdenes de Carga
    Route::resource('ordenes-carga', OrdenCargaController::class);
    Route::patch('ordenes-carga/{ordenCarga}/estado', [OrdenCargaController::class, 'cambiarEstado'])
        ->name('ordenes-carga.cambiar-estado');
    
    // Datos Maestros
    Route::resource('unidades', \App\Http\Controllers\Web\UnidadController::class);
    Route::resource('operadores', \App\Http\Controllers\Web\OperadorController::class);
    Route::resource('rutas', \App\Http\Controllers\Web\RutaController::class);
    Route::resource('tarifas', \App\Http\Controllers\Web\TarifaController::class);
    Route::resource('centros-costo', \App\Http\Controllers\Web\CentroCostoController::class);
    Route::resource('proveedores', \App\Http\Controllers\Web\ProveedorController::class);
    Route::resource('bancos', \App\Http\Controllers\Web\BancoController::class);
    Route::resource('cuentas-bancarias', \App\Http\Controllers\Web\CuentaBancariaController::class);
    Route::resource('conceptos', \App\Http\Controllers\Web\ConceptoController::class);
    Route::resource('impuestos', \App\Http\Controllers\Web\ImpuestoController::class);
});

require __DIR__.'/auth.php';
