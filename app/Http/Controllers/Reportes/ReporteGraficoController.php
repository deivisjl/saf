<?php

namespace App\Http\Controllers\Reportes;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ReporteGraficoController extends Controller
{

    private $meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportes.reporte-grafico.index');
    }

    public function comprasPorCategoria(Request $request)
    {
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

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
                        ->join('detalle_compra as dc','c.id','dc.compra_id')
                        ->join('producto as p','dc.producto_id','p.id')
                        ->select('p.id','p.nombre',DB::raw('COUNT(dc.cantidad) as cantidad'))
                        ->whereBetween('c.created_at', [$desde, $hasta])
                        ->groupBy('p.id','p.nombre')
                        ->get();

            $series = array();
            $etiquetas = array();

            foreach ($registros as $key => $item) 
            {
                $series[$key] = $item->cantidad;
                $etiquetas[$key] = $item->nombre;
            }

            $respuesta = array('series' => $series, 'etiquetas' => $etiquetas);

            return response()->json(['data' => $respuesta]);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function ventasPorCategoria(Request $request)
    {
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

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
                        ->join('detalle_venta as dv','v.id','dv.venta_id')
                        ->join('producto as p','dv.producto_id','p.id')
                        ->select('p.id','p.nombre',DB::raw('COUNT(dv.cantidad) as cantidad'))
                        ->whereBetween('v.created_at', [$desde, $hasta])
                        ->groupBy('p.id','p.nombre')
                        ->get();

            $series = array();
            $etiquetas = array();

            foreach ($registros as $key => $item) 
            {
                $series[$key] = $item->cantidad;
                $etiquetas[$key] = $item->nombre;
            }

            $respuesta = array('series' => $series, 'etiquetas' => $etiquetas);

            return response()->json(['data' => $respuesta]);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function categoriasMasVendidas(Request $request)
    {
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

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
                        ->join('detalle_venta as dv','v.id','dv.venta_id')
                        ->join('producto as p','dv.producto_id','p.id')
                        ->join('categoria as ct','p.categoria_id','ct.id')
                        ->select('ct.id','ct.nombre',DB::raw('COUNT(dv.cantidad) as cantidad'))
                        ->whereBetween('v.created_at', [$desde, $hasta])
                        ->groupBy('ct.id','ct.nombre')
                        ->get();

            $series = array();
            $etiquetas = array();

            foreach ($registros as $key => $item) 
            {
                $series[$key] = $item->cantidad;
                $etiquetas[$key] = $item->nombre;
            }

            $respuesta = array('series' => $series, 'etiquetas' => $etiquetas);

            return response()->json(['data' => $respuesta]);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function existenciasEnInventario()
    {
        try 
        {   
            $registros = DB::table('inventario as i')
                        ->join('producto as p','i.producto_id','p.id')
                        ->select('p.id','p.nombre','i.stock as cantidad')
                        ->get();

            $series = array();
            $etiquetas = array();

            foreach ($registros as $key => $item) 
            {
                $series[$key] = $item->cantidad;
                $etiquetas[$key] = $item->nombre;
            }

            $respuesta = array('series' => $series, 'etiquetas' => $etiquetas);

            return response()->json(['data' => $respuesta]);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function ventasPorMes(Request $request)
    {
        
        $rules = [
            'desde'=>'required|date',
            'hasta'=>'required|date'
        ];

        $this->validate($request, $rules);

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
            
            $registros = DB::table('venta')
                        ->select(DB::raw('SUM(monto) as monto'),DB::raw('DATE_FORMAT(created_at,"%m-%Y") as mes'))
                        ->whereBetween('created_at', [$desde, $hasta])
                        ->groupBy(DB::raw('DATE_FORMAT(created_at,"%m-%Y")'))
                        ->orderBy(DB::raw('DATE_FORMAT(created_at,"%m-%Y")'),'desc')
                        ->get(); 

            $series = array();
            $etiquetas = array();

            $meses = $this->parsearMeses($registros);

            foreach ($meses as $key => $item) 
            {
                $series[$key] = $item['monto'];
                $etiquetas[$key] = $item['mes'];
            }

            $respuesta = array('series' => $series, 'etiquetas' => $etiquetas);

            return response()->json(['data' => $respuesta]);
        } 
        catch (\Exception $ex) 
        {
            return response()->json(['error' => $ex->getMessage()],423);
        }
    }

    public function parsearMeses($data)
    {
        $respuesta = array();

        foreach ($data as $key => $item) 
        {
            $fecha = $item->mes;

            $numero = explode("-",$fecha);

            if($numero[0] < 10)
            {
                $numero2 = explode("0",$numero[0]);
            }
            else
            {
                $numero2[1] = $numero[0];
            }

            $respuesta[$key] = array('mes' => $this->meses[$numero2[1]].' '.$numero[1], 'monto' => (float)$item->monto);
        }

        return $respuesta;
    }
}
