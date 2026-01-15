<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoControlador extends Controller
{
    // Muestra la lista y filtra por nombre o código
    public function listarProductos(Request $solicitud)
    {
        $textoBusqueda = $solicitud->get('buscar');

        $todosLosProductos = Producto::where('nombreProducto', 'LIKE', '%' . $textoBusqueda . '%')
            ->orWhere('codigoBarras', 'LIKE', '%' . $textoBusqueda . '%')
            ->orderBy('nombreProducto', 'asc')
            ->get();

        return view('inventario', compact('todosLosProductos', 'textoBusqueda'));
    }

    // Procesa el formulario del Modal
    public function guardarProducto(Request $solicitud)
    {
        $datosValidados = $solicitud->validate([
            'nombreProducto' => 'required|string|max:255',
            'codigoBarras'   => 'nullable|string',
            'precioCompra'   => 'required|numeric',
            'precioVenta'    => 'required|numeric',
            'existencias'    => 'required|integer',
        ]);

        Producto::create([
            'nombreProducto'      => $datosValidados['nombreProducto'],
            'codigoBarras'        => $datosValidados['codigoBarras'],
            'precioCompra'        => $datosValidados['precioCompra'],
            'precioVenta'         => $datosValidados['precioVenta'],
            'existenciasActuales' => $datosValidados['existencias'],
            'empresaId'           => 1, // ID de la empresa de prueba
        ]);

        return redirect()->route('productos.listar')->with('exito', '¡Producto guardado!');
    }
}