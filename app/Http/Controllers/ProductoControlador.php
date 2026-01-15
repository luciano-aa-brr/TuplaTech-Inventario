<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoControlador extends Controller
{
    public function listarProductos(Request $solicitud)
    {
        $textoBusqueda = $solicitud->get('buscar');
        $categorias = Categoria::all();

        $todosLosProductos = Producto::with('categoria')
            ->where(function($query) use ($textoBusqueda) {
                $query->where('nombreProducto', 'LIKE', '%' . $textoBusqueda . '%')
                      ->orWhere('codigoBarras', 'LIKE', '%' . $textoBusqueda . '%');
            })
            ->orderBy('nombreProducto', 'asc')
            ->get();

        return view('inventario', compact('todosLosProductos', 'textoBusqueda', 'categorias'));
    }

    public function guardarProducto(Request $solicitud)
    {
        $res = $solicitud->validate([
            'nombreProducto' => 'required|string|max:255',
            'precioCompra' => 'required|numeric',
            'precioVenta' => 'required|numeric',
            'existencias' => 'required|integer',
            'categoriaId' => 'nullable|exists:Categorias,id'
        ]);

        Producto::create([
            'nombreProducto' => $res['nombreProducto'],
            'codigoBarras' => $solicitud->codigoBarras,
            'precioCompra' => $res['precioCompra'],
            'precioVenta' => $res['precioVenta'],
            'existenciasActuales' => $res['existencias'],
            'categoriaId' => $res['categoriaId'],
            'empresaId' => 1, 
        ]);

        return redirect()->back()->with('exito', 'Producto creado');
    }

    public function actualizarProducto(Request $solicitud, $id)
    {
        $producto = Producto::findOrFail($id);
        
        $producto->update([
            'nombreProducto' => $solicitud->nombreProducto,
            'codigoBarras' => $solicitud->codigoBarras,
            'precioCompra' => $solicitud->precioCompra,
            'precioVenta' => $solicitud->precioVenta,
            'existenciasActuales' => $solicitud->existencias,
            'categoriaId' => $solicitud->categoriaId,
        ]);

        return redirect()->back()->with('exito', 'Producto actualizado');
    }
}