<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;
use App\Models\Categoria;
use App\Models\Producto;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Línea 14: Creamos la empresa base ---
        $miEmpresa = Empresa::create([
            'nombreEmpresa' => 'TuplaTech Shop',
            'rutEmpresa' => '12.345.678-9' // Asegúrate que la migración tenga este campo
        ]);

        // --- Línea 20: Categorías dinámicas ---
        $catAudio = Categoria::create(['nombreCategoria' => 'Audio', 'empresaId' => $miEmpresa->id]);
        $catPerifericos = Categoria::create(['nombreCategoria' => 'Periféricos', 'empresaId' => $miEmpresa->id]);
        $catAccesorios = Categoria::create(['nombreCategoria' => 'Accesorios', 'empresaId' => $miEmpresa->id]);

        // --- Línea 25: Productos de prueba para el Dashboard ---
        Producto::create([
            'nombreProducto' => 'Audífonos Gamer RGB',
            'codigoBarras' => '750123456789',
            'precioCompra' => 15000,
            'precioVenta' => 25000,
            'existenciasActuales' => 12,
            'categoriaId' => $catAudio->id,
            'empresaId' => $miEmpresa->id
        ]);

        Producto::create([
            'nombreProducto' => 'Mouse Óptico',
            'codigoBarras' => '750987654321',
            'precioCompra' => 8000,
            'precioVenta' => 15000,
            'existenciasActuales' => 4, // Disparará alerta de stock crítico
            'categoriaId' => $catPerifericos->id,
            'empresaId' => $miEmpresa->id
        ]);
    }
}