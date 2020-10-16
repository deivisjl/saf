<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estado';

    const PENDIENTE = '1';
    const CANCELADO = '2';
    
    protected $fillable = [
        'id','nombre'
    ];
}
