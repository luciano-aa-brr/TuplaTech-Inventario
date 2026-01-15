<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoControlador;

Route::get('/', function () {
    return view('welcome');

});

//ruta para ver inventario
Route::get('/inventario', [ProductoControlador::class, 'listarProductos'])->name('productos.listar');

//ruta para guaardar producto
Route::post('/productos/guardar', [ProductoControlador::class, 'guardarProducto'])->name('productos.guardar');



