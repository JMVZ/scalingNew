<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tarifa;
use App\Models\Cliente;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TarifaController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $tarifas = Tarifa::with(['cliente', 'ruta'])
            ->porEmpresa($empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->when($request->cliente_id, function ($query, $clienteId) {
                $query->where('cliente_id', $clienteId);
            })
            ->when($request->vigentes, function ($query) {
                $query->vigentes();
            })
            ->when($request->activo !== null, function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('vigencia_desde', 'desc')
            ->paginate(15)
            ->withQueryString();

        $clientes = Cliente::porEmpresa($empresaId)->activos()->orderBy('nombre')->get();

        return Inertia::render('Catalogos/Tarifas/Index', [
            'tarifas' => $tarifas,
            'clientes' => $clientes,
            'filters' => $request->only(['search', 'cliente_id', 'vigentes', 'activo']),
        ]);
    }

    public function create(): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $clientes = Cliente::porEmpresa($empresaId)->activos()->orderBy('nombre')->get();
        $rutas = Ruta::porEmpresa($empresaId)->activos()->orderBy('nombre')->get();

        return Inertia::render('Catalogos/Tarifas/Create', [
            'clientes' => $clientes,
            'rutas' => $rutas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_id' => 'nullable|exists:clientes,id',
            'ruta_id' => 'nullable|exists:rutas,id',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|string|max:10',
            'vigencia_desde' => 'required|date',
            'vigencia_hasta' => 'nullable|date|after_or_equal:vigencia_desde',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : true;

        Tarifa::create($validated);

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa creada exitosamente');
    }

    public function edit(Tarifa $tarifa): Response
    {
        if ($tarifa->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $empresaId = auth()->user()->empresa_id;
        
        $clientes = Cliente::porEmpresa($empresaId)->activos()->orderBy('nombre')->get();
        $rutas = Ruta::porEmpresa($empresaId)->activos()->orderBy('nombre')->get();

        return Inertia::render('Catalogos/Tarifas/Edit', [
            'tarifa' => $tarifa->load(['cliente', 'ruta']),
            'clientes' => $clientes,
            'rutas' => $rutas,
        ]);
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        if ($tarifa->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cliente_id' => 'nullable|exists:clientes,id',
            'ruta_id' => 'nullable|exists:rutas,id',
            'precio' => 'required|numeric|min:0',
            'moneda' => 'required|string|max:10',
            'vigencia_desde' => 'required|date',
            'vigencia_hasta' => 'nullable|date|after_or_equal:vigencia_desde',
            'activo' => 'boolean',
        ]);

        $tarifa->update($validated);

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa actualizada exitosamente');
    }

    public function destroy(Tarifa $tarifa)
    {
        if ($tarifa->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $tarifa->delete();

        return redirect()->route('tarifas.index')
            ->with('success', 'Tarifa eliminada exitosamente');
    }
}
