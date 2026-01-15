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
    Schema::create('Productos', function (Blueprint $table) {
        $table->id();
        $table->string('nombreProducto');
        $table->string('codigoBarras')->nullable(); // Opcional, por si no tiene escáner
        $table->decimal('precioCompra', 10, 2);     // Ejemplo: 1500.50
        $table->decimal('precioVenta', 10, 2);      // Ejemplo: 2000.00
        $table->integer('existenciasActuales')->default(0);
        $table->integer('stockMinimo')->default(5); // Para alertas de stock bajo
        
        // Conexión con la empresa (Multi-tenancy)
        $table->foreignId('empresaId')->constrained('Empresas')->onDelete('cascade');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
