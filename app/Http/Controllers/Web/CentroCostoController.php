<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CentroCosto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CentroCostoController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $centrosCosto = CentroCosto::porEmpresa($empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
                });
            })
            ->when($request->activo !== null, function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('codigo')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/CentrosCosto/Index', [
            'centrosCosto' => $centrosCosto,
            'filters' => $request->only(['search', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/CentrosCosto/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:centros_costo,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : true;

        CentroCosto::create($validated);

        return redirect()->route('centros-costo.index')
            ->with('success', 'Centro de costo creado exitosamente');
    }

    public function edit(CentroCosto $centrosCosto): Response
    {
        if ($centrosCosto->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Catalogos/CentrosCosto/Edit', [
            'centroCosto' => $centrosCosto,
        ]);
    }

    public function update(Request $request, CentroCosto $centrosCosto)
    {
        if ($centrosCosto->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:centros_costo,codigo,' . $centrosCosto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $centrosCosto->update($validated);

        return redirect()->route('centros-costo.index')
            ->with('success', 'Centro de costo actualizado exitosamente');
    }

    public function destroy(CentroCosto $centrosCosto)
    {
        if ($centrosCosto->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        if ($centrosCosto->ordenesCarga()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el centro de costo porque tiene órdenes de carga asociadas');
        }

        $centrosCosto->delete();

        return redirect()->route('centros-costo.index')
            ->with('success', 'Centro de costo eliminado exitosamente');
    }
}
