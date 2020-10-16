<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoVenta extends Model
{
    protected $table = 'pago_venta';

     protected $fillable = [
        'id', 'factura_venta_id','monto'
    ];
}
