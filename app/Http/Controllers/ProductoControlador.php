<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoControlador extends Controller
{
    // Función para mostrar la tabla
    public function listarProductos()
    {
        $todosLosProductos = Producto::all();
        return view('inventario', compact('todosLosProductos'));
    }

    // Función para guardar (Asegúrate que esté DENTRO de la clase)
    public function guardarProducto(Request $solicitud)
    {
        // Validar datos
        $datosValidados = $solicitud->validate([
            'nombreProducto' => 'required|string|max:255',
            'codigoBarras'   => 'nullable|string',
            'precioCompra'   => 'required|numeric',
            'precioVenta'    => 'required|numeric',
            'existencias'    => 'required|integer',
        ]);

        // Crear en la DB
        Producto::create([
            'nombreProducto'      => $datosValidados['nombreProducto'],
            'codigoBarras'        => $datosValidados['codigoBarras'],
            'precioCompra'        => $datosValidados['precioCompra'],
            'precioVenta'         => $datosValidados['precioVenta'],
            'existenciasActuales' => $datosValidados['existencias'],
            'empresaId'           => 1, // ID de prueba
        ]);

        return redirect()->route('productos.listar');
    }
}