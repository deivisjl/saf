<?php

namespace App\Http\Controllers\Compra;

use App\Compra;
use App\Estado;
use App\Producto;
use App\FormaPago;
use App\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('compra.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formas = FormaPago::all();
        $estados = Estado::all();
        $proveedores = Proveedor::all();
        $productos = Producto::all();
        
        return view('compra.create',['formas' => $formas, 'estados' => $estados, 'proveedores' => $proveedores, 'productos' => $productos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("c.id","c.factura_no","p.nombre","c.monto","fp.nombre","e.nombre");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $compras = DB::table('compra as c') 
                ->join('proveedor as p','c.proveedor_id','p.id')
                ->join('forma_pago as fp','c.forma_pago_id','fp.id')
                ->join('estado as e','c.estado_id','e.id')
                ->select('c.id','c.factura_no','c.monto','p.nombre as proveedor','fp.nombre as forma_pago','e.nombre as estado') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('compra as c') 
                ->join('proveedor as p','c.proveedor_id','p.id')
                ->join('forma_pago as fp','c.forma_pago_id','fp.id')
                ->join('estado as e','c.estado_id','e.id')                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $compras,
            );
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Compra $compra)
    {
        //
    }
}
