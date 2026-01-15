<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Impuesto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ImpuestoController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $impuestos = Impuesto::query()
            ->where('empresa_id', $empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('codigo', 'like', "%{$search}%")
                      ->orWhere('nombre', 'like', "%{$search}%");
                });
            })
            ->when($request->tipo, function ($query, $tipo) {
                $query->where('tipo', $tipo);
            })
            ->when($request->has('activo'), function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('codigo')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/Impuestos/Index', [
            'impuestos' => $impuestos,
            'filters' => $request->only(['search', 'tipo', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Impuestos/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:impuestos,codigo',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'tasa' => 'required|numeric|min:0|max:1',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;

        Impuesto::create($validated);

        return redirect()->route('impuestos.index')
            ->with('success', 'Impuesto creado exitosamente.');
    }

    public function edit(Impuesto $impuesto): Response
    {
        return Inertia::render('Catalogos/Impuestos/Edit', [
            'impuesto' => $impuesto,
        ]);
    }

    public function update(Request $request, Impuesto $impuesto)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:10|unique:impuestos,codigo,' . $impuesto->id,
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'tasa' => 'required|numeric|min:0|max:1',
            'descripcion' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $impuesto->update($validated);

        return redirect()->route('impuestos.index')
            ->with('success', 'Impuesto actualizado exitosamente.');
    }

    public function destroy(Impuesto $impuesto)
    {
        $impuesto->delete();

        return redirect()->route('impuestos.index')
            ->with('success', 'Impuesto eliminado exitosamente.');
    }
}
