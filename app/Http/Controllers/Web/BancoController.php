<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Banco;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BancoController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $bancos = Banco::query()
            ->where('empresa_id', $empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('codigo', 'like', "%{$search}%");
                });
            })
            ->when($request->has('activo'), function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/Bancos/Index', [
            'bancos' => $bancos,
            'filters' => $request->only(['search', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Bancos/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:bancos,codigo',
            'swift' => 'nullable|string|max:11',
            'pais' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;

        Banco::create($validated);

        return redirect()->route('bancos.index')
            ->with('success', 'Banco creado exitosamente.');
    }

    public function edit(Banco $banco): Response
    {
        return Inertia::render('Catalogos/Bancos/Edit', [
            'banco' => $banco,
        ]);
    }

    public function update(Request $request, Banco $banco)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:bancos,codigo,' . $banco->id,
            'swift' => 'nullable|string|max:11',
            'pais' => 'nullable|string|max:100',
            'activo' => 'boolean',
        ]);

        $banco->update($validated);

        return redirect()->route('bancos.index')
            ->with('success', 'Banco actualizado exitosamente.');
    }

    public function destroy(Banco $banco)
    {
        $banco->delete();

        return redirect()->route('bancos.index')
            ->with('success', 'Banco eliminado exitosamente.');
    }
}
