<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'Productos';

    protected $fillable = [
        'nombreProducto',
        'codigoBarras',
        'precioCompra',
        'precioVenta',
        'existenciasActuales',
        'stockMinimo',
        'empresaId',
        'categoriaId'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoriaId');
    }
} // <-- Asegúrate de que esta llave de cierre exista