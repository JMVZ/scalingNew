<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['Ingreso', 'Egreso']); // Ingreso (facturación) o Egreso (gastos)
            $table->string('categoria')->nullable(); // Transporte, Combustible, Mantenimiento, etc.
            $table->string('cuenta_contable')->nullable();
            $table->boolean('aplica_iva')->default(true);
            $table->boolean('aplica_retencion')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'codigo']);
            $table->index(['empresa_id', 'tipo']);
            $table->index(['empresa_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conceptos');
    }
};
