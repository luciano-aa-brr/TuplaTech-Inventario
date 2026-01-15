<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Creamos una Empresa de prueba
        $miEmpresa = Empresa::create([
            'nombreEmpresa' => 'Minimarket El Socio',
            'identificadorFiscal' => '12345678-9',
            'correoContacto' => 'contacto@elsocio.com',
        ]);

        // 2. Creamos un Usuario administrador para esa empresa
        Usuario::create([
            'nombreUsuario' => 'AdminTupla',
            'correoElectronico' => 'admin@tuplatech.com',
            'contrasenia' => Hash::make('123456'), // Siempre encriptada
            'rolUsuario' => 'Administrador',
            'empresaId' => $miEmpresa->id,
        ]);

        // 3. Creamos algunos Productos de ejemplo
        $productos = [
            ['nombre' => 'Coca Cola 1.5L', 'compra' => 1200, 'venta' => 1800, 'stock' => 20],
            ['nombre' => 'Pan de Molde', 'compra' => 1500, 'venta' => 2200, 'stock' => 10],
            ['nombre' => 'Leche Entera 1L', 'compra' => 800, 'venta' => 1100, 'stock' => 15],
            ['nombre' => 'Café Instantáneo', 'compra' => 3000, 'venta' => 4500, 'stock' => 8],
            ['nombre' => 'Aceite de Girasol', 'compra' => 1800, 'venta' => 2500, 'stock' => 12],
        ];

        foreach ($productos as $p) {
            Producto::create([
                'nombreProducto' => $p['nombre'],
                'codigoBarras' => '780' . rand(1000, 9999),
                'precioCompra' => $p['compra'],
                'precioVenta' => $p['venta'],
                'existenciasActuales' => $p['stock'],
                'stockMinimo' => 5,
                'empresaId' => $miEmpresa->id,
            ]);
        }
    }
}
