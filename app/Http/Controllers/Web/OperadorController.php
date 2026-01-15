<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OperadorController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $operadores = Operador::porEmpresa($empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('numero_operador', 'like', "%{$search}%")
                      ->orWhere('nombres', 'like', "%{$search}%")
                      ->orWhere('apellido_paterno', 'like', "%{$search}%")
                      ->orWhere('apellido_materno', 'like', "%{$search}%")
                      ->orWhere('telefono', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%")
                      ->orWhere('licencia', 'like', "%{$search}%");
                });
            })
            ->when($request->activo !== null, function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->orderBy('nombres')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Catalogos/Operadores/Index', [
            'operadores' => $operadores,
            'filters' => $request->only(['search', 'activo']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Catalogos/Operadores/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_operador' => 'required|string|max:255|unique:operadores,numero_operador,NULL,id,empresa_id,' . auth()->user()->empresa_id,
            'activo' => 'boolean',
            'fecha_contratacion' => 'nullable|date',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'nombres' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'licencia' => 'nullable|string|max:255',
            'vencimiento_licencia' => 'nullable|date|after_or_equal:fecha_contratacion',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;
        $validated['activo'] = $request->has('activo') ? $request->boolean('activo') : true;

        Operador::create($validated);

        return redirect()->route('operadores.index')
            ->with('success', 'Operador creado exitosamente');
    }

    public function edit(Operador $operadore): Response
    {
        if ($operadore->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Catalogos/Operadores/Edit', [
            'operador' => $operadore,
        ]);
    }

    public function update(Request $request, Operador $operadore)
    {
        if ($operadore->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'numero_operador' => 'required|string|max:255|unique:operadores,numero_operador,' . $operadore->id . ',id,empresa_id,' . auth()->user()->empresa_id,
            'activo' => 'boolean',
            'fecha_contratacion' => 'nullable|date',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'nombres' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'licencia' => 'nullable|string|max:255',
            'vencimiento_licencia' => 'nullable|date|after_or_equal:fecha_contratacion',
        ]);

        $operadore->update($validated);

        return redirect()->route('operadores.index')
            ->with('success', 'Operador actualizado exitosamente');
    }

    public function destroy(Operador $operadore)
    {
        if ($operadore->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        if ($operadore->ordenesCarga()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el operador porque tiene órdenes de carga asociadas');
        }

        $operadore->delete();

        return redirect()->route('operadores.index')
            ->with('success', 'Operador eliminado exitosamente');
    }
}
