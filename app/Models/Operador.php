<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operador extends Model
{
    protected $table = 'operadores';
    
    protected $fillable = [
        'empresa_id',
        'numero_operador',
        'activo',
        'fecha_contratacion',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'telefono',
        'correo',
        'licencia',
        'vencimiento_licencia',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_contratacion' => 'date',
        'vencimiento_licencia' => 'date',
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

    // Accessor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return trim("{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}");
    }
}
