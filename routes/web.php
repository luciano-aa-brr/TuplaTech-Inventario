<?php

use App\Http\Controllers\ProductoControlador;
use Illuminate\Support\Facades\Route;

// --- Línea 6: Ver inventario y Buscar ---
Route::get('/inventario', [ProductoControlador::class, 'listarProductos'])->name('productos.listar');

// --- Línea 9: Crear (Producto y Categoría) ---
Route::post('/productos/guardar', [ProductoControlador::class, 'guardarProducto'])->name('productos.guardar');
Route::post('/categorias/guardar', [ProductoControlador::class, 'guardarCategoria'])->name('categorias.guardar');

// --- Línea 13: Editar y Eliminar ---
Route::put('/productos/actualizar/{id}', [ProductoControlador::class, 'actualizarProducto'])->name('productos.actualizar');
Route::delete('/productos/eliminar/{id}', [ProductoControlador::class, 'eliminarProducto'])->name('productos.eliminar');