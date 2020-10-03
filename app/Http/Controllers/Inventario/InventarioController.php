<?php

namespace App\Http\Controllers\Inventario;

use App\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inventario.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Inventario  $inventario
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("i.id","c.nombre","p.nombre","i.stock");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $productos = DB::table('inventario as i') 
                ->join('producto as p','i.producto_id','p.id')
                ->join('categoria as c','p.categoria_id','c.id')
                ->select('i.id','c.nombre as categoria','p.nombre as producto','i.stock','p.stock_minimo as minimo')
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('inventario as i') 
                ->join('producto as p','i.producto_id','p.id')
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
}
