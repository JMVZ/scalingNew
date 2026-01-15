<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProveedorController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $proveedores = Proveedor::query()
            ->where('empresa_id', $empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('razon_social', 'like', "%{$search}%")
                      ->orWhere('nombre_comercial', 'like', "%{$search}%")
                      ->orWhere('rfc', 'like', "%{$search}%");
                });
            })
            ->when($request->tipo, function ($query, $tipo) {
                $query->where('tipo_proveedor', $tipo);
            })
            ->when($request->has('activo'), function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('razon_social')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/Proveedores/Index', [
            'proveedores' => $proveedores,
            'filters' => $request->only(['search', 'tipo', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Proveedores/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'rfc' => 'required|string|size:13|unique:proveedores,rfc',
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'pais' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contacto_nombre' => 'nullable|string|max:255',
            'contacto_telefono' => 'nullable|string|max:20',
            'contacto_email' => 'nullable|email|max:255',
            'tipo_proveedor' => 'nullable|string|max:100',
            'banco' => 'nullable|string|max:255',
            'cuenta_bancaria' => 'nullable|string|max:50',
            'clabe' => 'nullable|string|size:18',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;

        Proveedor::create($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente.');
    }

    public function edit(Proveedor $proveedore): Response
    {
        return Inertia::render('Catalogos/Proveedores/Edit', [
            'proveedor' => $proveedore,
        ]);
    }

    public function update(Request $request, Proveedor $proveedore)
    {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'rfc' => 'required|string|size:13|unique:proveedores,rfc,' . $proveedore->id,
            'direccion' => 'nullable|string',
            'ciudad' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'pais' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contacto_nombre' => 'nullable|string|max:255',
            'contacto_telefono' => 'nullable|string|max:20',
            'contacto_email' => 'nullable|email|max:255',
            'tipo_proveedor' => 'nullable|string|max:100',
            'banco' => 'nullable|string|max:255',
            'cuenta_bancaria' => 'nullable|string|max:50',
            'clabe' => 'nullable|string|size:18',
            'notas' => 'nullable|string',
            'activo' => 'boolean',
        ]);

        $proveedore->update($validated);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente.');
    }

    public function destroy(Proveedor $proveedore)
    {
        $proveedore->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente.');
    }
}
