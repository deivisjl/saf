<?php

namespace App\Http\Controllers\Administrar;

use App\Estado;
use App\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrar.cliente.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('administrar.cliente.create');
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
                'nombres' => 'required',
                'apellidos' => 'required',
                'nit' => 'required|unique:cliente',
                'telefono' => 'required',
                'direccion' => 'required'
            ];

            $this->validate($request, $rules);

            $registro = new Cliente();
            $registro->nombres = $request->nombres;
            $registro->apellidos = $request->apellidos;
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
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("id","nombres","apellidos","nit");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $clientes = DB::table('cliente') 
                ->select('id','nombres','apellidos','nit') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('cliente')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $clientes,
            );

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function edit(Cliente $cliente)
    {
        return view('administrar.cliente.edit',['cliente' => $cliente]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        try 
        {
            $rules = [
                'nombres' => 'required',
                'apellidos' => 'required',
                'nit' => 'required|unique:cliente,nit,'.$cliente->id,
                'telefono' => 'required',
                'direccion' => 'required'
            ];

            $this->validate($request, $rules);

            $cliente->nombres = $request->nombres;
            $cliente->apellidos = $request->apellidos;
            $cliente->nit = $request->nit;
            $cliente->telefono = $request->telefono;
            $cliente->direccion = $request->direccion;
            $cliente->save();

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
     * @param  \App\Cliente  $cliente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cliente $cliente)
    {
        try 
        {

            $cliente->delete();

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
