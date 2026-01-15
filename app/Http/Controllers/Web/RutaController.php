<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RutaController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $rutas = Ruta::porEmpresa($empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('origen', 'like', "%{$search}%")
                      ->orWhere('destino', 'like', "%{$search}%");
                });
            })
            ->when($request->activo !== null, function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/Rutas/Index', [
            'rutas' => $rutas,
            'filters' => $request->only(['search', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Rutas/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'distancia_km' => 'nullable|numeric|min:0',
            'tiempo_estimado_horas' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : true;

        Ruta::create($validated);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta creada exitosamente');
    }

    public function edit(Ruta $ruta): Response
    {
        if ($ruta->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Catalogos/Rutas/Edit', [
            'ruta' => $ruta,
        ]);
    }

    public function update(Request $request, Ruta $ruta)
    {
        if ($ruta->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'origen' => 'required|string|max:255',
            'destino' => 'required|string|max:255',
            'distancia_km' => 'nullable|numeric|min:0',
            'tiempo_estimado_horas' => 'nullable|numeric|min:0',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $ruta->update($validated);

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta actualizada exitosamente');
    }

    public function destroy(Ruta $ruta)
    {
        if ($ruta->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        if ($ruta->ordenesCarga()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la ruta porque tiene órdenes de carga asociadas');
        }

        $ruta->delete();

        return redirect()->route('rutas.index')
            ->with('success', 'Ruta eliminada exitosamente');
    }
}
