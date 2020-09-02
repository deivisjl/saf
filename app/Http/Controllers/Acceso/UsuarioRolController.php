<?php

namespace App\Http\Controllers\Acceso;

use App\Rol;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UsuarioRolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accesos.usuarios-roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","nombres","apellidos","email");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $usuarios = DB::table('users') 
                ->select('id','nombres','apellidos','email') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->whereNull('deleted_at')
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('users')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->whereNull('deleted_at')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $usuarios,
            );
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::findOrFail($id);

        return view('accesos.usuarios-roles.roles',['usuario' => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try 
        {
            $usuario = User::findOrFail($id);
            $usuario->syncRoles($request->roles);

            return $this->successResponse('Registro actualizado con éxito');
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function listado_roles($id)
    {
        $usuario = User::findOrFail($id);

        $roles = $usuario->roles()
                        ->get(['role_id'])
                        ->pluck('pivot')
                        ->values();

        return $this->showAll(collect($roles));
    }
}
