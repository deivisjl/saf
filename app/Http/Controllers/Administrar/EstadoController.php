<?php

namespace App\Http\Controllers\Administrar;

use App\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrar.estados.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrar.estados.create');
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

            $estado = new Estado();
            $estado->nombre = $request->nombre;
            $estado->save();

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
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","nombre");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $permisos = DB::table('estado') 
                ->select('id','nombre') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('estado')                               
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
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function edit(Estado $estado)
    {
        return view('administrar.estados.edit',['estado' => $estado]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Estado $estado)
    {
        try 
        {
            $rules = [
                'nombre' => 'required'
            ];

            $this->validate($request, $rules);

            $estado->nombre = $request->nombre;
            $estado->save();

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
     * @param  \App\Estado  $estado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Estado $estado)
    {
        try 
        {

            $estado->delete();

            return $this->successResponse('Registro eliminado con éxito');
        } 
        catch (\Exception $e) 
        {
            if ($ex instanceof QueryException) {
                $codigo = $ex->errorInfo[1];
    
                if ($codigo == 1451) {
                    return $this->errorResponse('No se puede eliminar porque tiene registros asociados');
                }
            }
            return $this->errorResponse($e->getMessage());
        }
    }
}
