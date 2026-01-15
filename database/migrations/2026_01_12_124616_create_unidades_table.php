<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('numero_unidad')->unique();
            $table->string('placas')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->integer('año')->nullable();
            $table->string('tipo')->nullable(); // Tractocamion, Trailer, Caja, etc
            $table->decimal('capacidad_carga', 10, 2)->nullable();
            $table->string('numero_serie')->nullable();
            $table->string('tarjeta_circulacion')->nullable();
            $table->string('poliza_seguro')->nullable();
            $table->date('vencimiento_seguro')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['empresa_id', 'numero_unidad']);
            $table->index(['empresa_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
