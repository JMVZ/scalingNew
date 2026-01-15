<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas_bancarias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('banco_id')->constrained('bancos')->onDelete('cascade');
            $table->string('nombre'); // Nombre descriptivo de la cuenta
            $table->string('numero_cuenta');
            $table->string('clabe', 18)->nullable();
            $table->string('moneda', 3)->default('MXN');
            $table->string('tipo_cuenta')->nullable(); // Cheques, Ahorro, Inversión
            $table->decimal('saldo_inicial', 15, 2)->default(0);
            $table->decimal('saldo_actual', 15, 2)->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'banco_id']);
            $table->index(['empresa_id', 'activo']);
            $table->index('numero_cuenta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas_bancarias');
    }
};
