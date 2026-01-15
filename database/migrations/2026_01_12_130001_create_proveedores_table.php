<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('razon_social');
            $table->string('nombre_comercial')->nullable();
            $table->string('rfc', 13)->unique();
            $table->text('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal', 10)->nullable();
            $table->string('pais')->default('México');
            $table->string('telefono', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('contacto_nombre')->nullable();
            $table->string('contacto_telefono', 20)->nullable();
            $table->string('contacto_email')->nullable();
            $table->string('tipo_proveedor')->nullable(); // Combustible, Mantenimiento, Servicios, etc.
            $table->string('banco')->nullable();
            $table->string('cuenta_bancaria')->nullable();
            $table->string('clabe', 18)->nullable();
            $table->text('notas')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'rfc']);
            $table->index(['empresa_id', 'activo']);
            $table->index('tipo_proveedor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
