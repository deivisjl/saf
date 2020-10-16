<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cuenta';

    const CAJA = '1';
    const CLIENTES = '2';
    const PROVEEDORES = '3';
    const VENTAS = '4';

     protected $fillable = [
        'id', 'nombre'
    ];
}
