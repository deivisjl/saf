<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaVenta extends Model
{
    protected $table = 'factura_venta';

     protected $fillable = [
        'id', 'numero','monto','saldo','venta_id','estado_id'
    ];
}
