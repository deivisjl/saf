<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

     protected $fillable = [
        'id', 'name'
    ];

    public function rol_permiso()
    {
    	return $this->hasMany(RolPermiso::class,'role_id');
    }
}
