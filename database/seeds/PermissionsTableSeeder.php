<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Permission::insert([
        	['name' => 'Crear permisos','guard_name' => 'web'],
        	['name' => 'Navegar permisos','guard_name' => 'web'],
        	['name' => 'Editar permisos','guard_name' => 'web'],
        	['name' => 'Eliminar permisos','guard_name' => 'web']
        ]);

        Permission::insert([
        	['name' => 'Crear roles','guard_name' => 'web'],
        	['name' => 'Navegar roles','guard_name' => 'web'],
        	['name' => 'Editar roles','guard_name' => 'web'],
        	['name' => 'Eliminar roles','guard_name' => 'web']
        ]);

        $rol = Role::create(['name' => 'Administrador'])->givePermissionTo(Permission::all());

        $user = User::first();
        $user->assignRole($rol);
    }
}
