<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    protected $fillable = [
        'empresa_id',
        'numero_cliente',
        'nombre_fiscal',
        'nombre_comercial',
        'rfc',
        'estatus',
        'fecha_alta',
        'direccion',
        'telefono',
        'correo',
    ];

    protected $casts = [
        'fecha_alta' => 'date',
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
        return $query->where('estatus', 'activo');
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return $this->nombre_comercial ?: $this->nombre_fiscal;
    }
}
