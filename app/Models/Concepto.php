<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Concepto extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'codigo',
        'nombre',
        'descripcion',
        'tipo', // Ingreso (facturación) o Egreso (gastos)
        'categoria', // Transporte, Combustible, Mantenimiento, Administrativo, etc.
        'cuenta_contable',
        'aplica_iva',
        'aplica_retencion',
        'activo',
    ];

    protected $casts = [
        'aplica_iva' => 'boolean',
        'aplica_retencion' => 'boolean',
        'activo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relaciones
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'Ingreso');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'Egreso');
    }
}
