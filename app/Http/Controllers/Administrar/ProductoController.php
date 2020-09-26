<?php

namespace App\Http\Controllers\Administrar;

use App\Producto;
use App\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('administrar.producto.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        
        return view('administrar.producto.create',['categorias' => $categorias]);
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
                'categoria' => 'required',
                'stock_minimo' => 'required|numeric',
                'stock_maximo' => 'required|numeric',
            ];

            $this->validate($request, $rules);

            $registro = new Producto();
            $registro->nombre = $request->nombre;
            $registro->categoria_id = $request->categoria;
            $registro->stock_minimo = $request->stock_minimo;
            $registro->stock_maximo = $request->stock_maximo;
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
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("p.id","p.nombre","c.nombre","p.stock_minimo","p.stock_maximo");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $productos = DB::table('producto as p')
                ->join('categoria as c','p.categoria_id','c.id') 
                ->select('p.id','p.nombre','c.nombre as categoria','p.stock_minimo','p.stock_maximo') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('producto as p')                            
                ->join('categoria as c','p.categoria_id','c.id')    
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $productos,
            );

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();

        return view('administrar.producto.edit',['categorias' => $categorias, 'producto' => $producto]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        try 
        {
            $rules = [
                'nombre' => 'required',
                'categoria' => 'required',
                'stock_minimo' => 'required|numeric',
                'stock_maximo' => 'required|numeric',
            ];

            $this->validate($request, $rules);

            $producto->nombre = $request->nombre;
            $producto->categoria_id = $request->categoria;
            $producto->stock_minimo = $request->stock_minimo;
            $producto->stock_maximo = $request->stock_maximo;
            $producto->save();

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
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        try 
        {

            $producto->delete();

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
