<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdenCargaRequest;
use App\Http\Requests\UpdateOrdenCargaRequest;
use App\Models\Cliente;
use App\Models\OrdenCarga;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrdenCargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
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
            ->paginate(15)
            ->withQueryString();

        $clientes = Cliente::porEmpresa($empresaId)
            ->activos()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return Inertia::render('OrdenesCarga/Index', [
            'ordenes' => $ordenes,
            'clientes' => $clientes,
            'filters' => $request->only(['search', 'estado', 'cliente_id']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $clientes = Cliente::porEmpresa($empresaId)
            ->activos()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return Inertia::render('OrdenesCarga/Create', [
            'clientes' => $clientes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrdenCargaRequest $request)
    {
        $orden = OrdenCarga::create($request->validated());

        return redirect()->route('ordenes-carga.index')
            ->with('success', 'Orden de carga creada exitosamente. Folio: ' . $orden->folio);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenCarga $ordenCarga): Response
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $ordenCarga->load(['cliente', 'empresa']);

        return Inertia::render('OrdenesCarga/Show', [
            'orden' => $ordenCarga,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrdenCarga $ordenCarga): Response
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $empresaId = auth()->user()->empresa_id;
        
        $clientes = Cliente::porEmpresa($empresaId)
            ->activos()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $ordenCarga->load('cliente');

        return Inertia::render('OrdenesCarga/Edit', [
            'orden' => $ordenCarga,
            'clientes' => $clientes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdenCargaRequest $request, OrdenCarga $ordenCarga)
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $ordenCarga->update($request->validated());

        return redirect()->route('ordenes-carga.index')
            ->with('success', 'Orden de carga actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenCarga $ordenCarga)
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        // Solo permitir eliminar órdenes en planeación
        if ($ordenCarga->estado !== 'planeacion') {
            return back()->with('error', 'Solo se pueden eliminar órdenes en estado de planeación');
        }

        $ordenCarga->delete();

        return redirect()->route('ordenes-carga.index')
            ->with('success', 'Orden de carga eliminada exitosamente');
    }

    /**
     * Cambiar el estado de una orden
     */
    public function cambiarEstado(Request $request, OrdenCarga $ordenCarga)
    {
        // Verificar que la orden pertenezca a la empresa del usuario
        if ($ordenCarga->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $request->validate([
            'estado' => 'required|in:planeacion,en_ejecucion,finalizado'
        ]);

        $ordenCarga->update([
            'estado' => $request->estado
        ]);

        return back()->with('success', 'Estado actualizado exitosamente');
    }
}
