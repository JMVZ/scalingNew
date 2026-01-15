<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Concepto;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConceptoController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $conceptos = Concepto::query()
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

        return Inertia::render('Catalogos/Conceptos/Index', [
            'conceptos' => $conceptos,
            'filters' => $request->only(['search', 'tipo', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Conceptos/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:conceptos,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:Ingreso,Egreso',
            'categoria' => 'nullable|string|max:100',
            'cuenta_contable' => 'nullable|string|max:50',
            'aplica_iva' => 'boolean',
            'aplica_retencion' => 'boolean',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;

        Concepto::create($validated);

        return redirect()->route('conceptos.index')
            ->with('success', 'Concepto creado exitosamente.');
    }

    public function edit(Concepto $concepto): Response
    {
        return Inertia::render('Catalogos/Conceptos/Edit', [
            'concepto' => $concepto,
        ]);
    }

    public function update(Request $request, Concepto $concepto)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:50|unique:conceptos,codigo,' . $concepto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:Ingreso,Egreso',
            'categoria' => 'nullable|string|max:100',
            'cuenta_contable' => 'nullable|string|max:50',
            'aplica_iva' => 'boolean',
            'aplica_retencion' => 'boolean',
            'activo' => 'boolean',
        ]);

        $concepto->update($validated);

        return redirect()->route('conceptos.index')
            ->with('success', 'Concepto actualizado exitosamente.');
    }

    public function destroy(Concepto $concepto)
    {
        $concepto->delete();

        return redirect()->route('conceptos.index')
            ->with('success', 'Concepto eliminado exitosamente.');
    }
}
