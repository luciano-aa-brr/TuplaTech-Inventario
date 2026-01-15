<?php

use App\Http\Controllers\ProductoControlador;
use Illuminate\Support\Facades\Route;

// Ruta principal para ver el inventario
Route::get('/inventario', [ProductoControlador::class, 'listarProductos'])->name('productos.listar');

// Ruta para procesar el guardado del producto
Route::post('/productos/guardar', [ProductoControlador::class, 'guardarProducto'])->name('productos.guardar');

// Redirección opcional para que al entrar a la raíz "/" nos mande al inventario
Route::get('/', function () {
    return redirect()->route('productos.listar');
});


