<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruta extends Model
{
    protected $fillable = [
        'empresa_id',
        'nombre',
        'origen',
        'destino',
        'distancia_km',
        'tiempo_estimado_horas',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'distancia_km' => 'decimal:2',
        'tiempo_estimado_horas' => 'decimal:2',
    ];

    // Relaciones
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function ordenesCarga(): HasMany
    {
        return $this->hasMany(OrdenCarga::class);
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
}
