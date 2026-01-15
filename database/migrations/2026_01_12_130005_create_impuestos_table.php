<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('codigo', 10)->unique();
            $table->string('nombre');
            $table->string('tipo'); // IVA, ISR, Retención IVA, Retención ISR, IEPS
            $table->decimal('tasa', 5, 4); // Ej: 0.1600 para IVA 16%
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'codigo']);
            $table->index(['empresa_id', 'tipo']);
            $table->index(['empresa_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impuestos');
    }
};
