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
        Schema::create('Empresas', function (Blueprint $table) {
        $table->id(); // id automático
        $table->string('nombreEmpresa');
        $table->string('identificadorFiscal')->nullable(); // RUT, NIT, etc.
        $table->string('correoContacto');
        $table->boolean('estaActiva')->default(true);
        $table->timestamps(); // Crea fechaCreacion y fechaActualizacion (created_at, updated_at)
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
