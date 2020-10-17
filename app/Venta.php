<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';

     protected $fillable = [
        'id', 'cliente_id','factura_no','serie','monto','forma_pago_id',
    ];
}
