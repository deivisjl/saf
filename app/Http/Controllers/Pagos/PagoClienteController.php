<?php

namespace App\Http\Controllers\Pagos;

use App\Estado;
use App\PagoVenta;
use App\FacturaVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PagoClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pagos.pago-cliente.index');
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

                $factura = FacturaVenta::findOrFail($request->id);

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

                $pago = new PagoVenta();
                $pago->monto = $request->get('monto');
                $pago->factura_venta_id = $factura->id;
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
        $registro = FacturaVenta::with('pago_venta')
                                    ->where('factura_venta.id',$id)
                                    ->first();

        return view('pagos.pago-cliente.detalle',['registro' => $registro]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ordenadores = array("fv.id","cl.nombres","fv.numero","fv.monto","fv.saldo");

        $columna = $request['order'][0]["column"];
        
        $criterio = $request['search']['value'];

        $registros = DB::table('factura_venta as fv')
                ->join('venta as v','fv.venta_id','v.id')
                ->join('cliente as cl','v.cliente_id','cl.id') 
                ->select('fv.id','fv.monto','fv.saldo','fv.numero', DB::raw('CONCAT_WS(" ",nombres,"",apellidos) as nombre')) 
                ->where('fv.estado_id',Estado::PENDIENTE)
                ->where($ordenadores[$columna], 'LIKE', '%' . $criterio . '%')
                ->orderBy($ordenadores[$columna], $request['order'][0]["dir"])
                ->skip($request['start'])
                ->take($request['length'])
                ->get();
              
        $count = DB::table('factura_venta as fv')
                ->join('venta as v','fv.venta_id','v.id')
                ->join('cliente as cl','v.cliente_id','cl.id') 
                ->select('fv.id','fv.monto','fv.saldo','fv.numero', DB::raw('CONCAT_WS(" ",nombres,"",apellidos) as nombre')) 
                ->where('fv.estado_id',Estado::PENDIENTE)                               
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
        $factura = FacturaVenta::findOrfail($id);

        return view('pagos.pago-cliente.abonar',['factura' => $factura]);
    }
}
