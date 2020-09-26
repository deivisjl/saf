<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'producto';

     protected $fillable = [
        'id', 'nombre', 'categoria_id', 'stock_minimo', 'stock_maximo'
    ];
}
