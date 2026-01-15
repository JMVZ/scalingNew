<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $empresaId = auth()->user()->empresa_id;
        
        $clientes = Cliente::porEmpresa($empresaId)
            ->when($request->activo, function ($query) {
                $query->activos();
            })
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('rfc', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombre')
            ->paginate($request->per_page ?? 15);

        return response()->json($clientes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClienteRequest $request): JsonResponse
    {
        $cliente = Cliente::create($request->validated());

        return response()->json([
            'message' => 'Cliente creado exitosamente',
            'data' => $cliente
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente): JsonResponse
    {
        // Verificar que el cliente pertenezca a la empresa del usuario
        if ($cliente->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        return response()->json($cliente);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClienteRequest $request, Cliente $cliente): JsonResponse
    {
        // Verificar que el cliente pertenezca a la empresa del usuario
        if ($cliente->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $cliente->update($request->validated());

        return response()->json([
            'message' => 'Cliente actualizado exitosamente',
            'data' => $cliente
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente): JsonResponse
    {
        // Verificar que el cliente pertenezca a la empresa del usuario
        if ($cliente->empresa_id !== auth()->user()->empresa_id) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        // Verificar si tiene órdenes de carga
        if ($cliente->ordenesCarga()->count() > 0) {
            return response()->json([
                'message' => 'No se puede eliminar el cliente porque tiene órdenes de carga asociadas'
            ], 422);
        }

        $cliente->delete();

        return response()->json([
            'message' => 'Cliente eliminado exitosamente'
        ]);
    }
}
