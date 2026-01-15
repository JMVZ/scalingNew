<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('numero_operador')->unique();
            $table->boolean('activo')->default(true);
            $table->date('fecha_contratacion')->nullable();
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('nombres');
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('licencia')->nullable();
            $table->date('vencimiento_licencia')->nullable();
            $table->timestamps();
            
            $table->index(['empresa_id', 'numero_operador']);
            $table->index(['empresa_id', 'activo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operadores');
    }
};
