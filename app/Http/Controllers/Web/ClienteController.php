<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClienteController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $clientes = Cliente::porEmpresa($empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('numero_cliente', 'like', "%{$search}%")
                      ->orWhere('nombre_fiscal', 'like', "%{$search}%")
                      ->orWhere('nombre_comercial', 'like', "%{$search}%")
                      ->orWhere('rfc', 'like', "%{$search}%")
                      ->orWhere('telefono', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%");
                });
            })
            ->when($request->estatus, function ($query, $estatus) {
                $query->where('estatus', $estatus);
            })
            ->orderBy('nombre_fiscal')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Clientes/Index', [
            'clientes' => $clientes,
            'filters' => $request->only(['search', 'estatus']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Clientes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_cliente' => 'required|string|max:255|unique:clientes,numero_cliente,NULL,id,empresa_id,' . auth()->user()->empresa_id,
            'nombre_fiscal' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'estatus' => 'required|in:activo,inactivo',
            'fecha_alta' => 'nullable|date',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;

        Cliente::create($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente');
    }

    public function edit(Cliente $cliente): Response
    {
        if ($cliente->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        return Inertia::render('Clientes/Edit', [
            'cliente' => $cliente,
        ]);
    }

    public function update(Request $request, Cliente $cliente)
    {
        if ($cliente->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        $validated = $request->validate([
            'numero_cliente' => 'required|string|max:255|unique:clientes,numero_cliente,' . $cliente->id . ',id,empresa_id,' . auth()->user()->empresa_id,
            'nombre_fiscal' => 'required|string|max:255',
            'nombre_comercial' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'estatus' => 'required|in:activo,inactivo',
            'fecha_alta' => 'nullable|date',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy(Cliente $cliente)
    {
        if ($cliente->empresa_id !== auth()->user()->empresa_id) {
            abort(403, 'No autorizado');
        }

        if ($cliente->ordenesCarga()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el cliente porque tiene órdenes de carga asociadas');
        }

        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}
