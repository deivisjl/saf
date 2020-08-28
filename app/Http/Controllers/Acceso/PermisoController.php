<?php

namespace App\Http\Controllers\Acceso;

use App\RolPermiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class PermisoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:Crear permisos')->only(['create','store']);
        $this->middleware('permission:Navegar permisos')->only(['index']);
        $this->middleware('permission:Editar permisos')->only(['edit','update']);
        $this->middleware('permission:Navegar permisos')->only(['show']);
        $this->middleware('permission:Eliminar permisos')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('accesos.permisos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('accesos.permisos.create');
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
                'nombre' => 'required'
            ];

            $this->validate($request, $rules);

            $permiso = new Permission();
            $permiso->name = $request->nombre;
            $permiso->guard_name = "web";
            $permiso->save();

            return $this->successResponse('Registro guardado con éxito');
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","name");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $permisos = DB::table('permissions') 
                ->select('id','name as nombre') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('permissions')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $permisos,
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
        $permiso = Permission::findOrfail($id);

        return view('accesos.permisos.edit',['permiso' => $permiso]);
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
            $rules = [
                'nombre' => 'required'
            ];

            $this->validate($request, $rules);

            $permiso = Permission::findOrFail($id);
            $permiso->name = $request->nombre;
            $permiso->save();

            return $this->successResponse('Registro actualizado con éxito');
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $permiso = Permission::findOrFail($id);
            $verificar = RolPermiso::where('permission_id',$id)->first();

            if($verificar)
            {
                throw new \Exception("No se puede eliminar porque tiene registros asociados", 1);
            }

            $permiso->delete();

            return $this->successResponse('Registro eliminado con éxito');
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }
}
