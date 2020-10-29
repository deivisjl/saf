<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'venta';

     protected $fillable = [
        'id', 'cliente_id','factura_no','serie','monto','forma_pago_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function forma_pago()
    {
        return $this->belongsTo(FormaPago::class);
    }

    public function detalle_venta()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
