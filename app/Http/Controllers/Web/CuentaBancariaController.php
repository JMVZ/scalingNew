<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CuentaBancaria;
use App\Models\Banco;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CuentaBancariaController extends Controller
{
    public function index(Request $request): Response
    {
        $empresaId = auth()->user()->empresa_id;
        
        $cuentasBancarias = CuentaBancaria::query()
            ->with('banco')
            ->where('empresa_id', $empresaId)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('numero_cuenta', 'like', "%{$search}%")
                      ->orWhere('clabe', 'like', "%{$search}%");
                });
            })
            ->when($request->banco_id, function ($query, $bancoId) {
                $query->where('banco_id', $bancoId);
            })
            ->when($request->has('activo'), function ($query) use ($request) {
                $query->where('activo', $request->activo);
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        $bancos = Banco::where('empresa_id', $empresaId)->activos()->orderBy('nombre')->get();

        return Inertia::render('Catalogos/CuentasBancarias/Index', [
            'cuentasBancarias' => $cuentasBancarias,
            'bancos' => $bancos,
            'filters' => $request->only(['search', 'banco_id', 'activo']),
        ]);
    }

    public function create(): Response
    {
        $empresaId = auth()->user()->empresa_id;
        $bancos = Banco::where('empresa_id', $empresaId)->activos()->orderBy('nombre')->get();

        return Inertia::render('Catalogos/CuentasBancarias/Create', [
            'bancos' => $bancos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'nombre' => 'required|string|max:255',
            'numero_cuenta' => 'required|string|max:50',
            'clabe' => 'nullable|string|size:18',
            'moneda' => 'nullable|string|max:3',
            'tipo_cuenta' => 'nullable|string|max:50',
            'saldo_inicial' => 'nullable|numeric|min:0',
            'saldo_actual' => 'nullable|numeric',
            'activo' => 'boolean',
        ]);

        $validated['empresa_id'] = auth()->user()->empresa_id;

        CuentaBancaria::create($validated);

        return redirect()->route('cuentas-bancarias.index')
            ->with('success', 'Cuenta bancaria creada exitosamente.');
    }

    public function edit(CuentaBancaria $cuentasBancaria): Response
    {
        $empresaId = auth()->user()->empresa_id;
        $bancos = Banco::where('empresa_id', $empresaId)->activos()->orderBy('nombre')->get();

        return Inertia::render('Catalogos/CuentasBancarias/Edit', [
            'cuentaBancaria' => $cuentasBancaria->load('banco'),
            'bancos' => $bancos,
        ]);
    }

    public function update(Request $request, CuentaBancaria $cuentasBancaria)
    {
        $validated = $request->validate([
            'banco_id' => 'required|exists:bancos,id',
            'nombre' => 'required|string|max:255',
            'numero_cuenta' => 'required|string|max:50',
            'clabe' => 'nullable|string|size:18',
            'moneda' => 'nullable|string|max:3',
            'tipo_cuenta' => 'nullable|string|max:50',
            'saldo_inicial' => 'nullable|numeric|min:0',
            'saldo_actual' => 'nullable|numeric',
            'activo' => 'boolean',
        ]);

        $cuentasBancaria->update($validated);

        return redirect()->route('cuentas-bancarias.index')
            ->with('success', 'Cuenta bancaria actualizada exitosamente.');
    }

    public function destroy(CuentaBancaria $cuentasBancaria)
    {
        $cuentasBancaria->delete();

        return redirect()->route('cuentas-bancarias.index')
            ->with('success', 'Cuenta bancaria eliminada exitosamente.');
    }
}
