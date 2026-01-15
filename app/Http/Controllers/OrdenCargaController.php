<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrdenCargaRequest;
use App\Http\Requests\UpdateOrdenCargaRequest;
use App\Models\OrdenCarga;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdenCargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $empresaId = auth()->user()->empresa_id;
        
        $ordenes = OrdenCarga::with(['cliente', 'empresa'])
            ->porEmpresa($empresaId)
            ->when($request->estado, function ($query, $estado) {
                $query->porEstado($estado);
            })
            ->when($request->cliente_id, function ($query, $clienteId) {
                $query->where('cliente_id', $clienteId);
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('folio', 'like', "%{$search}%")
                      ->orWhere('origen', 'like', "%{$search}%")
                      ->orWhere('destino', 'like', "%{$search}%")
                      ->orWhere('operador_texto', 'like', "%{$search}%")
                      ->orWhere('placas_unidad', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return response()->json($ordenes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdenCargaRequest $request): JsonResponse
    {
        $orden = OrdenCarga::create($request->validated());
        $orden->load(['cliente', 'empresa']);

        return response()->json([
            'message' => 'Orden de carga creada exitosamente',
            'data' => $orden
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenCarga $ordenCarga): JsonResponse
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $ordenCarga->load(['cliente', 'empresa']);

        return response()->json($ordenCarga);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdenCargaRequest $request, OrdenCarga $ordenCarga): JsonResponse
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $ordenCarga->update($request->validated());
        $ordenCarga->load(['cliente', 'empresa']);

        return response()->json([
            'message' => 'Orden de carga actualizada exitosamente',
            'data' => $ordenCarga
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenCarga $ordenCarga): JsonResponse
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        // Solo permitir eliminar órdenes en planeación
        if ($ordenCarga->estado !== 'planeacion') {
            return response()->json([
                'message' => 'Solo se pueden eliminar órdenes en estado de planeación'
            ], 422);
        }

        $ordenCarga->delete();

        return response()->json([
            'message' => 'Orden de carga eliminada exitosamente'
        ]);
    }

    /**
     * Cambiar el estado de una orden de carga
     */
    public function cambiarEstado(Request $request, OrdenCarga $ordenCarga): JsonResponse
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $request->validate([
            'estado' => 'required|in:planeacion,en_ejecucion,finalizado'
        ]);

        $ordenCarga->update([
            'estado' => $request->estado
        ]);

        return response()->json([
            'message' => 'Estado actualizado exitosamente',
            'data' => $ordenCarga
        ]);
    }
}
