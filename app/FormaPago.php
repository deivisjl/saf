<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormaPago extends Model
{
    protected $table = 'forma_pago';

    const CREDITO = '1';
    const CONTADO = '2';

    protected $fillable = [
        'id','nombre'
    ];
}
