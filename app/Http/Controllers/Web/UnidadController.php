<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UnidadController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $unidades = Unidad::porEmpresa($empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('numero_unidad', 'like', "%{$search}%")
                      ->orWhere('placas', 'like', "%{$search}%")
                      ->orWhere('marca', 'like', "%{$search}%")
                      ->orWhere('modelo', 'like', "%{$search}%");
                });
            })
            ->when($request->activo !== null, function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('numero_unidad')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/Unidades/Index', [
            'unidades' => $unidades,
            'filters' => $request->only(['search', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Unidades/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_unidad' => 'required|string|max:255|unique:unidades,numero_unidad',
            'placas' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'año' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'tipo' => 'nullable|string|max:255',
            'capacidad_carga' => 'nullable|numeric|min:0',
            'numero_serie' => 'nullable|string|max:255',
            'tarjeta_circulacion' => 'nullable|string|max:255',
            'poliza_seguro' => 'nullable|string|max:255',
            'vencimiento_seguro' => 'nullable|date',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : true;

        Unidad::create($validated);

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad creada exitosamente');
    }

    public function edit(Unidad $unidade): Response
    {
        if ($unidade->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Catalogos/Unidades/Edit', [
            'unidad' => $unidade,
        ]);
    }

    public function update(Request $request, Unidad $unidade)
    {
        if ($unidade->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'numero_unidad' => 'required|string|max:255|unique:unidades,numero_unidad,' . $unidade->id,
            'placas' => 'nullable|string|max:255',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'año' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'tipo' => 'nullable|string|max:255',
            'capacidad_carga' => 'nullable|numeric|min:0',
            'numero_serie' => 'nullable|string|max:255',
            'tarjeta_circulacion' => 'nullable|string|max:255',
            'poliza_seguro' => 'nullable|string|max:255',
            'vencimiento_seguro' => 'nullable|date',
            'activo' => 'boolean',
        ]);

        $unidade->update($validated);

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad actualizada exitosamente');
    }

    public function destroy(Unidad $unidade)
    {
        if ($unidade->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        if ($unidade->ordenesCarga()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la unidad porque tiene órdenes de carga asociadas');
        }

        $unidade->delete();

        return redirect()->route('unidades.index')
            ->with('success', 'Unidad eliminada exitosamente');
    }
}
