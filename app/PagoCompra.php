<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoCompra extends Model
{
    protected $table = 'pago_compra';

     protected $fillable = [
        'id', 'factura_compra_id','monto'
    ];
}
