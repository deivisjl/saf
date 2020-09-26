<?php

namespace App\Http\Controllers\Administrar;

use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrar.proveedor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrar.proveedor.create');
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
                'nit' => 'required|unique:proveedor',
                'telefono' => 'required',
                'direccion' => 'required'
            ];

            $this->validate($request, $rules);

            $registro = new Proveedor();
            $registro->nombre = $request->nombre;
            $registro->nit = $request->nit;
            $registro->telefono = $request->telefono;
            $registro->direccion = $request->direccion;
            $registro->save();

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
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","nombre","nit");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $proveedores = DB::table('proveedor') 
                ->select('id','nombre','nit') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('proveedor')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $proveedores,
            );
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Proveedor $proveedore)
    {
        return view('administrar.proveedor.edit',['proveedor' => $proveedore]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proveedor $proveedore)
    {
        try 
        {
            $rules = [
                'nombre' => 'required',
                'nit' => 'required|unique:proveedor,nit,'.$proveedore->id,
                'telefono' => 'required',
                'direccion' => 'required'
            ];

            $this->validate($request, $rules);

            $proveedore->nombre = $request->nombre;
            $proveedore->nit = $request->nit;
            $proveedore->telefono = $request->telefono;
            $proveedore->direccion = $request->direccion;
            $proveedore->save();

            return $this->successResponse('Registro guardado con éxito');
        } 
        catch (\Exception $e) 
        {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proveedor  $proveedor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proveedor $proveedore)
    {
        try 
        {

            $proveedore->delete();

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
