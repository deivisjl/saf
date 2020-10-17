<?php

namespace App\Http\Controllers\Compra;

use App\Compra;
use App\Estado;
use App\Producto;
use App\FormaPago;
use App\Proveedor;
use App\DetalleCompra;
use App\FacturaCompra;
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
        $proveedores = Proveedor::all();
        $productos = Producto::all();
        
        return view('compra.create',['formas' => $formas, 'proveedores' => $proveedores, 'productos' => $productos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'factura' => 'required',
            'fecha' => 'required|date',
            'forma_pago' => 'required|numeric',
            'productos' => 'required|array',
            'proveedor' => 'required|numeric',
            'total' => 'required|numeric'
        ];

        $this->validate($request,$rules);

        try 
        {
            return DB::transaction(function () use($request){
            
                $compra = new Compra();
                $compra->factura_no = $request->get('factura');
                $compra->monto = $request->get('total');
                $compra->fecha_emision = $request->get('fecha');
                $compra->forma_pago_id = $request->get('forma_pago');
                $compra->proveedor_id = $request->get('proveedor');
                $compra->save();
    
                foreach ($request->get('productos') as $key => $item) {
                    $detalle_compra = new DetalleCompra();
                    $detalle_compra->producto_id = $item['producto']['id'];
                    $detalle_compra->compra_id = $compra->id;
                    $detalle_compra->cantidad = $item['cantidad'];
                    $detalle_compra->precio_unitario = $item['precio'];
                    $detalle_compra->save();
                }

                $factura = new FacturaCompra();
                $factura->numero = $compra->factura_no;
                $factura->monto = $compra->monto;
                $factura->saldo = ($compra->forma_pago_id == FormaPago::CREDITO) ? $compra->monto : 0;
                $factura->compra_id = $compra->id;
                $factura->estado_id = ($compra->forma_pago_id == FormaPago::CREDITO) ? Estado::PENDIENTE : Estado::CANCELADO;
                $factura->save();

                return response()->json(['data' => 'Compra registrada con éxito'],200);
            });
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
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
                ->join('factura_compra as fc','fc.compra_id','c.id')
                ->join('estado as e','fc.estado_id','e.id')
                ->select('c.id','c.factura_no','c.monto','p.nombre as proveedor','fp.nombre as forma_pago','e.nombre as estado') 
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('compra as c') 
                ->join('proveedor as p','c.proveedor_id','p.id')
                ->join('forma_pago as fp','c.forma_pago_id','fp.id')
                ->join('factura_compra as fc','fc.compra_id','c.id')
                ->join('estado as e','fc.estado_id','e.id')                          
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
