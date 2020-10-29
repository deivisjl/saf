<?php

namespace App\Http\Controllers\Reportes;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReporteDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportes.reporte-documento.index');
    }

    public function reporteCompras(Request $request)
    {
        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));

            if($desde >= $hasta)
            {
                throw new \Exception("Debe seleccionar un rango válido", 1);
            }

            $diferencia = $hasta->diffInDays($desde);

            if($diferencia > 365)
            {
                throw new \Exception("El rango no debe ser mayor a un año", 1);    
            }

            $registros = DB::table('compra as c')
                            ->join('proveedor as p','c.proveedor_id','p.id')
                            ->select('c.id','c.factura_no','c.monto','c.fecha_emision','p.nombre','c.created_at')
                            ->whereBetween('c.created_at',[$desde,$hasta])
                            ->get();
            
            $fecha = Carbon::now()->format('dmY_h:m:s');
                
            $reporte = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('reporte-compra',['registros' => $registros, 'desde' => $desde, 'hasta' => $hasta])->setPaper('letter','landscape');
        
            return $reporte->download('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function reporteVentas(Request $request)
    {
        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));

            if($desde >= $hasta)
            {
                throw new \Exception("Debe seleccionar un rango válido", 1);
            }

            $diferencia = $hasta->diffInDays($desde);

            if($diferencia > 365)
            {
                throw new \Exception("El rango no debe ser mayor a un año", 1);    
            }

            $registros = DB::table('venta as v')
                            ->join('cliente as c','c.id','v.cliente_id')
                            ->select('v.id','v.factura_no','v.monto',DB::raw('CONCAT_WS(" ",c.nombres,"",c.apellidos) as cliente'),'v.created_at')
                            ->whereBetween('c.created_at',[$desde,$hasta])
                            ->get();
            
            $fecha = Carbon::now()->format('dmY_h:m:s');
                
            $reporte = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('reporte-venta',['registros' => $registros, 'desde' => $desde, 'hasta' => $hasta])->setPaper('letter','landscape');
        
            return $reporte->download('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function reporteInventario()
    {
        try 
        {
            $registros = DB::table('inventario as i')
                            ->join('producto as p','i.producto_id','p.id')
                            ->select('i.id','i.stock','p.nombre')
                            ->get();
            
            $fecha = Carbon::now()->format('dmY_h:m:s');
                
            $reporte = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('reporte-inventario',['registros' => $registros,])->setPaper('letter','landscape');
        
            return $reporte->download('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function reporteCuentasCobrar(Request $request)
    {
        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));

            if($desde >= $hasta)
            {
                throw new \Exception("Debe seleccionar un rango válido", 1);
            }

            $diferencia = $hasta->diffInDays($desde);

            if($diferencia > 365)
            {
                throw new \Exception("El rango no debe ser mayor a un año", 1);    
            }

            $registros = DB::table('factura_venta as fv')
                            ->join('venta as v','fv.venta_id','v.id')
                            ->join('cliente as c','c.id','v.cliente_id')
                            ->select('fv.saldo','c.telefono',DB::raw('CONCAT_WS(" ",c.nombres,"",c.apellidos) as cliente'),'fv.created_at')
                            ->where('fv.saldo','<>',0)
                            ->whereBetween('v.created_at',[$desde,$hasta])
                            ->get();
            
            $fecha = Carbon::now()->format('dmY_h:m:s');
                
            $reporte = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('reporte-cuentas-cobrar',['registros' => $registros, 'desde' => $desde, 'hasta' => $hasta])->setPaper('letter','landscape');
        
            return $reporte->download('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function reporteCuentasPagar(Request $request)
    {
        try 
        {
            $desde = Carbon::parse($request->get('desde')); 
            $hasta = Carbon::parse($request->get('hasta'));

            if($desde >= $hasta)
            {
                throw new \Exception("Debe seleccionar un rango válido", 1);
            }

            $diferencia = $hasta->diffInDays($desde);

            if($diferencia > 365)
            {
                throw new \Exception("El rango no debe ser mayor a un año", 1);    
            }

            $registros = DB::table('factura_compra as fc')
                            ->join('compra as c','fc.compra_id','c.id')
                            ->join('proveedor as p','p.id','c.proveedor_id')
                            ->select('fc.saldo','p.nombre','c.created_at','p.telefono')
                            ->where('fc.saldo','<>',0)
                            ->whereBetween('c.created_at',[$desde,$hasta])
                            ->get();
            
            $fecha = Carbon::now()->format('dmY_h:m:s');
                
            $reporte = \PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('reporte-cuentas-pagar',['registros' => $registros, 'desde' => $desde, 'hasta' => $hasta])->setPaper('letter','landscape');
        
            return $reporte->download('reporte_'.$fecha.'.pdf');
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }
}
