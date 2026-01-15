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
    Schema::create('Usuarios', function (Blueprint $table) {
        $table->id();
        $table->string('nombreUsuario');
        $table->string('correoElectronico')->unique();
        $table->string('contrasenia');
        $table->string('rolUsuario')->default('Vendedor'); // Administrador o Vendedor
        
        // La llave que conecta al usuario con su empresa
        $table->foreignId('empresaId')->constrained('Empresas'); 
        
        $table->rememberToken();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
