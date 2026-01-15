<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'Categorias';

    protected $fillable = [
        'nombreCategoria',
        'empresaId'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoriaId');
    }
}