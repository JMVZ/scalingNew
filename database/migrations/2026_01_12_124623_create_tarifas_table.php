<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('cascade');
            $table->foreignId('ruta_id')->nullable()->constrained('rutas')->onDelete('cascade');
            $table->string('nombre');
            $table->decimal('precio', 12, 2);
            $table->string('moneda')->default('MXN');
            $table->date('vigencia_desde');
            $table->date('vigencia_hasta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['empresa_id', 'cliente_id']);
            $table->index(['empresa_id', 'activo']);
            $table->index(['vigencia_desde', 'vigencia_hasta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifas');
    }
};
