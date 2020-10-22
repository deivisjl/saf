<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaCompra extends Model
{
    protected $table = 'factura_compra';

     protected $fillable = [
        'id', 'numero', 'monto','saldo','compra_id','estado_id'
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
    public function pago_compra()
    {
        return $this->hasMany(PagoCompra::class);
    }
}
