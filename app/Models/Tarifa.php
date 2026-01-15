<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tarifa extends Model
{
    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'ruta_id',
        'nombre',
        'precio',
        'moneda',
        'vigencia_desde',
        'vigencia_hasta',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'precio' => 'decimal:2',
        'vigencia_desde' => 'date',
        'vigencia_hasta' => 'date',
    ];

    // Relaciones
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function ruta(): BelongsTo
    {
        return $this->belongsTo(Ruta::class);
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

    public function scopeVigentes($query)
    {
        $hoy = now()->toDateString();
        return $query->where('vigencia_desde', '<=', $hoy)
            ->where(function ($q) use ($hoy) {
                $q->whereNull('vigencia_hasta')
                    ->orWhere('vigencia_hasta', '>=', $hoy);
            });
    }
}
