<?php

namespace App\Http\Controllers\Acceso;

use App\Rol;
use App\RolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RolController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Crear roles')->only(['create','store']);
        $this->middleware('permission:Navegar roles')->only(['index']);
        $this->middleware('permission:Editar roles')->only(['edit','update']);
        $this->middleware('permission:Navegar roles')->only(['show']);
        $this->middleware('permission:Eliminar roles')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accesos.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accesos.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try 
        {
            $rules = [
                'nombre' => 'required',
                'permisos' => 'nullable|array'
            ];

            $this->validate($request, $rules);

            return DB::transaction(function() use($request){

                $rol = new Rol();
                $rol->name = $request->nombre;
                $rol->guard_name = 'web';
                $rol->save();

                foreach ($request->permisos as $key => $value) {
                    RolPermiso::create([
                        'permission_id' => $value,
                        'role_id' => $rol->id
                    ]);
                }

                return $this->successResponse('Registro guardado con éxito');
            });
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","name");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $roles = DB::table('roles') 
                ->select('id','name as nombre') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('roles')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $roles,
            );
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rol = Rol::findOrFail($id);

        return view('accesos.roles.edit',['rol' => $rol]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rol $role)
    {
        try 
        {
            $rules = [
                'nombre' => 'required',
                'permisos' => 'nullable|array'
            ];

            $this->validate($request, $rules);

            return DB::transaction(function() use($request, $role){

                $role->rol_permiso()->delete();

                foreach ($request->permisos as $key => $value) {
                    RolPermiso::create([
                        'permission_id' => $value,
                        'role_id' => $role->id
                    ]);
                }

                $role->name = $request->get('nombre');
                $role->save();

                return $this->successResponse('Registro actualizado con éxito');
            });
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Rol  $rol
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rol $role)
    {
        try 
        {
            $verificar = RolPermiso::where('role_id',$role->id)->first();

            if($verificar)
            {
                throw new \Exception("No se puede eliminar porque tiene registros asociados", 1);
            }

            $role->delete();

            return $this->successResponse('Registro eliminado con éxito');
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function rol_permisos($id)
    {
        $permisos = RolPermiso::where('role_id',$id)->get();

        return $this->showAll($permisos);
    }

    public function roles()
    {
        $roles = Rol::all();

        return $this->showAll($roles);
    }
}
