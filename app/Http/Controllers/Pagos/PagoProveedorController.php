<?php

namespace App\Http\Controllers\Pagos;

use App\Estado;
use App\PagoCompra;
use App\FacturaCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PagoProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pagos.pago-proveedor.index');
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
            return DB::transaction(function () use ($request){
                $rules = [
                    'id' => 'required',
                    'monto' => 'required',
                ];
        
                $this->validate($request, $rules);

                $factura = FacturaCompra::findOrFail($request->id);

                if($factura->saldo < $request->get('monto'))
                {
                    throw new \Exception("El monto es mayor al saldo", 1);
                }

                $saldo = $factura->saldo - $request->get('monto');
                $factura->saldo = $saldo;
                if($saldo == 0)
                {
                    $factura->estado_id = Estado::CANCELADO;
                }
                $factura->save();

                $pago = new PagoCompra();
                $pago->monto = $request->get('monto');
                $pago->factura_compra_id = $factura->id;
                $pago->save();

                return response()->json(['data' => 'Registro generado con éxito'],200);
            });
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function detalle($id)
    {
        $registro = FacturaCompra::with('pago_compra')
                                    ->where('factura_compra.id',$id)
                                    ->first();

        return view('pagos.pago-proveedor.detalle',['registro' => $registro]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("fc.id","p.nombre","c.factura_no","fc.monto","fc.saldo");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $registros = DB::table('factura_compra as fc')
                ->join('compra as c','fc.compra_id','c.id')
                ->join('proveedor as p','c.proveedor_id','p.id') 
                ->select('fc.id','fc.monto','fc.saldo','c.factura_no','p.nombre') 
                ->where('fc.estado_id',Estado::PENDIENTE)
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('factura_compra as fc')
                ->join('compra as c','fc.compra_id','c.id')
                ->join('proveedor as p','c.proveedor_id','p.id') 
                ->where('fc.estado_id',Estado::PENDIENTE)                               
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->count();
               
        $data = array(
            'draw' => $request->draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $registros,
            );
        
        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function abonar($id)
    {
        $factura = FacturaCompra::findOrfail($id);

        return view('pagos.pago-proveedor.abonar',['factura' => $factura]);
    }
}
