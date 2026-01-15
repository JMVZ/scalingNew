<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordenes_carga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('restrict');
            $table->string('folio')->unique();
            $table->string('tipo_servicio');
            $table->string('ruta')->nullable();
            $table->decimal('tarifa', 10, 2);
            $table->enum('moneda', ['MXN', 'USD'])->default('MXN');
            $table->string('origen');
            $table->string('destino');
            $table->string('unidad_texto')->nullable();
            $table->string('placas_unidad')->nullable();
            $table->string('operador_texto')->nullable();
            $table->string('licencia_operador')->nullable();
            $table->string('remolque')->nullable();
            $table->string('placas_remolque')->nullable();
            $table->text('descripcion_mercancia')->nullable();
            $table->enum('estado', ['planeacion', 'en_ejecucion', 'finalizado'])->default('planeacion');
            $table->timestamps();
            
            $table->index(['empresa_id', 'estado']);
            $table->index(['empresa_id', 'folio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_carga');
    }
};
