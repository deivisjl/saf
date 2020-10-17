<?php

namespace App\Http\Controllers\Venta;

use App\Venta;
use App\Estado;
use App\Cliente;
use App\Producto;
use App\FormaPago;
use App\DetalleVenta;
use App\FacturaVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ventas.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes = Cliente::all();
        $formaPago = FormaPago::all();
        $productos = Producto::all();
        
        return view('ventas.create',['clientes' => $clientes, 'formaPago' => $formaPago, 'productos' => $productos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* 
        cliente: null
        factura: null
        fecha: null
        forma_pago: 1
        productos: [{,…}]
        0: {,…}
        cantidad: "1"
        precio: "1800.00"
        producto: {id: 1, nombre: "Tradicional ochavada", categoria_id: 1, stock_minimo: 2, stock_maximo: 5,…}
        subtotal: "1800.00"
        total: 1800
         */

         $rules =[
             'cliente' => 'required',
             'forma_pago' => 'required',
             'productos' => 'required|array',
             'total' => 'required|numeric',
         ];

         $this->validate($request, $rules);

         try 
         {
             return DB::transaction(function () use($request){
                 $venta = new Venta();
                 $venta->cliente_id = $request->get('cliente');
                 $venta->monto = $request->get('total');
                 $venta->forma_pago_id = $request->get('forma_pago');
                 $venta->save();

                 $venta->factura_no = $venta->id;
                 $venta->save();

                 foreach ($request->get('productos') as $key => $item) {
                     $detalle = new DetalleVenta();
                     $detalle->producto_id = $item['producto']['id'];
                     $detalle->venta_id = $venta->id;
                     $detalle->cantidad = $item['cantidad'];
                     $detalle->precio_unitario = $item['precio'];
                     $detalle->save();
                 }

                $factura = new FacturaVenta();
                $factura->numero = $venta->id;
                $factura->monto = $venta->monto;
                $factura->saldo = ($venta->forma_pago_id == FormaPago::CREDITO) ? $venta->monto : 0;
                $factura->venta_id = $venta->id;
                $factura->estado_id = ($venta->forma_pago_id == FormaPago::CREDITO) ? Estado::PENDIENTE : Estado::CANCELADO;
                $factura->save();

                return response()->json(['data' => 'Venta registrada con éxito'],200);
             });
         } 
         catch (\Exception $ex) 
         {
             return response()->json(['error' => $ex->getMessage()],423);
         }
        return response()->json(['data' => $request->all()],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("v.id","v.factura_no","c.nombres","v.monto","fp.nombre","e.nombre");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $ventas = DB::table('venta as v')
                    ->join('cliente as c','v.cliente_id','c.id')
                    ->join('forma_pago as fp','v.forma_pago_id','fp.id')
                    ->join('factura_venta as fv','fv.venta_id','v.id')
                    ->join('estado as e','fv.estado_id','e.id')
                    ->select('v.id','v.factura_no',DB::raw('CONCAT_WS(" ",c.nombres,"",c.apellidos) as cliente'),'v.monto','fp.nombre as forma_pago','e.nombre as estado')
                    ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                    ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                    ->skip($request['start'])
                    ->take($request['length'])
                    ->get();
              
        $count = DB::table('venta as v')
                ->join('cliente as c','v.cliente_id','c.id')
                ->join('forma_pago as fp','v.forma_pago_id','fp.id')
                ->join('factura_venta as fv','fv.venta_id','v.id')
                ->join('estado as e','fv.estado_id','e.id')                          
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $ventas,
            );
        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        //
    }
}
