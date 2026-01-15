<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre');
            $table->string('codigo', 10)->unique(); // Código bancario (ej: 012 para BBVA)
            $table->string('swift')->nullable();
            $table->string('pais')->default('México');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'codigo']);
            $table->index(['empresa_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bancos');
    }
};
