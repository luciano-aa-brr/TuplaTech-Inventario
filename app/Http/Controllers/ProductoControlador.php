<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Empresa;
use Illuminate\Http\Request;

class ProductoControlador extends Controller
{
    /**
     * Línea 14: Listado con Buscador y Estadísticas
     */
    public function listarProductos(Request $solicitud)
    {
        $textoBusqueda = $solicitud->get('buscar');
        $categorias = Categoria::all();

        // Buscamos productos que coincidan con nombre o código
        $todosLosProductos = Producto::with('categoria')
            ->where(function($query) use ($textoBusqueda) {
                $query->where('nombreProducto', 'LIKE', '%' . $textoBusqueda . '%')
                      ->orWhere('codigoBarras', 'LIKE', '%' . $textoBusqueda . '%');
            })
            ->orderBy('nombreProducto', 'asc')
            ->get();

        /**
         * Línea 32: Cálculos para el Dashboard
         */
        $inversionTotal = $todosLosProductos->sum(fn($p) => $p->precioCompra * $p->existenciasActuales);
        $gananciaProyectada = $todosLosProductos->sum(fn($p) => ($p->precioVenta - $p->precioCompra) * $p->existenciasActuales);
        $alertasStock = $todosLosProductos->where('existenciasActuales', '<', 5)->count();

        return view('inventario', compact(
            'todosLosProductos', 'textoBusqueda', 'categorias', 
            'inversionTotal', 'gananciaProyectada', 'alertasStock'
        ));
    }

    /**
     * Línea 47: Guardar Producto
     */
    public function guardarProducto(Request $solicitud)
    {
        $empresa = Empresa::first();
        Producto::create([
            'nombreProducto' => $solicitud->nombreProducto,
            'codigoBarras' => $solicitud->codigoBarras,
            'precioCompra' => $solicitud->precioCompra,
            'precioVenta' => $solicitud->precioVenta,
            'existenciasActuales' => $solicitud->existencias,
            'categoriaId' => $solicitud->categoriaId,
            'empresaId' => $empresa->id,
        ]);
        return redirect()->back()->with('exito', '¡Producto añadido!');
    }

    /**
     * Línea 65: Guardar Categoría
     */
    public function guardarCategoria(Request $solicitud)
    {
        $empresa = Empresa::first();
        Categoria::create(['nombreCategoria' => $solicitud->nombreCategoria, 'empresaId' => $empresa->id]);
        return redirect()->back()->with('exito', '¡Categoría creada!');
    }

    /**
     * Línea 72: Actualizar Producto
     */
    public function actualizarProducto(Request $solicitud, $id)
    {
        Producto::findOrFail($id)->update([
            'nombreProducto' => $solicitud->nombreProducto,
            'codigoBarras' => $solicitud->codigoBarras,
            'precioCompra' => $solicitud->precioCompra,
            'precioVenta' => $solicitud->precioVenta,
            'existenciasActuales' => $solicitud->existencias,
            'categoriaId' => $solicitud->categoriaId,
        ]);
        return redirect()->back()->with('exito', '¡Cambios guardados!');
    }

    /**
     * Línea 87: Eliminar Producto
     */
    public function eliminarProducto($id)
    {
        Producto::findOrFail($id)->delete();
        return redirect()->back()->with('exito', 'Producto eliminado');
    }
}