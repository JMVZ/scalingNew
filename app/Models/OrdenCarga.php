<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrdenCarga extends Model
{
    protected $table = 'ordenes_carga';

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'folio',
        'tipo_servicio',
        'ruta',
        'tarifa',
        'moneda',
        'origen',
        'destino',
        'unidad_texto',
        'placas_unidad',
        'operador_texto',
        'licencia_operador',
        'remolque',
        'placas_remolque',
        'descripcion_mercancia',
        'estado',
    ];

    protected $casts = [
        'tarifa' => 'decimal:2',
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

    // Boot para generar folio automáticamente
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orden) {
            if (empty($orden->folio)) {
                $orden->folio = static::generarFolio($orden->empresa_id);
            }
        });
    }

    // Generar folio único
    public static function generarFolio($empresaId): string
    {
        $anio = date('Y');
        $ultimaOrden = static::where('empresa_id', $empresaId)
            ->where('folio', 'LIKE', "OC-{$anio}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimaOrden) {
            $ultimoNumero = (int) Str::afterLast($ultimaOrden->folio, '-');
            $nuevoNumero = $ultimoNumero + 1;
        } else {
            $nuevoNumero = 1;
        }

        return sprintf('OC-%s-%05d', $anio, $nuevoNumero);
    }

    // Scopes
    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopePorEstado($query, $estado)
    {
        return $query->where('estado', $estado);
    }

    public function scopePlaneacion($query)
    {
        return $query->where('estado', 'planeacion');
    }

    public function scopeEnEjecucion($query)
    {
        return $query->where('estado', 'en_ejecucion');
    }

    public function scopeFinalizado($query)
    {
        return $query->where('estado', 'finalizado');
    }
}
