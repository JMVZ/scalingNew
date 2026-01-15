<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'empresa_id',
        'razon_social',
        'nombre_comercial',
        'rfc',
        'direccion',
        'ciudad',
        'estado',
        'codigo_postal',
        'pais',
        'telefono',
        'email',
        'contacto_nombre',
        'contacto_telefono',
        'contacto_email',
        'tipo_proveedor', // Combustible, Mantenimiento, Servicios, etc.
        'banco',
        'cuenta_bancaria',
        'clabe',
        'notas',
        'activo',
    ];

    protected $casts = [
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
        return $query->where('tipo_proveedor', $tipo);
    }
}
