<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compra';

     protected $fillable = [
        'id', 'factura_no', 'monto', 'fecha_emision', 'estado_id', 'forma_pago_id', 'proveedor_id'
    ];
}
