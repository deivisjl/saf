<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    protected $table = 'role_has_permissions';

     protected $fillable = [
        'permission_id', 'role_id'
    ];
}
