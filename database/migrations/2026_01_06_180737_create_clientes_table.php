<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('numero_cliente')->unique();
            $table->string('nombre_fiscal');
            $table->string('nombre_comercial')->nullable();
            $table->string('rfc')->nullable();
            $table->enum('estatus', ['activo', 'inactivo'])->default('activo');
            $table->date('fecha_alta')->nullable();
            $table->text('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->timestamps();
            
            $table->index(['empresa_id', 'numero_cliente']);
            $table->index(['empresa_id', 'estatus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
