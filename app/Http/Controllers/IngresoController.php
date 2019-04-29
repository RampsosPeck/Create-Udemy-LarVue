<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ingreso;
use App\DetalleIngreso;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class IngresoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar==''){
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
                'ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id','desc')->paginate(3);
        }else{
            $ingresos = Ingreso::join('personas','ingresos.idproveedor','=','personas.id')
            ->join('users','ingresos.idusuario','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
                'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
                'ingresos.estado','personas.nombre','users.usuario')
            ->where('ingresos.'.$criterio, 'like', '%'.$buscar.'%')->orderBy('ingresos.id','desc')->paginate(3);
        }
    
        //return $personas;
        return [
            'pagination'  => [
                'total'         => $ingresos->total(),
                'current_page'  => $ingresos->currentPage(),
                'per_page'      => $ingresos->perPage(),
                'last_page'     => $ingresos->lastPage(),
                'from'          => $ingresos->firstItem(),
                'to'            => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }

     
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        //implementamos un capturador de execpciones
        try{
            DB::beginTransaction();

            $mytime = Carbon::now('America/La_Paz');

            $ingreso = new  Ingreso();
            $ingreso->idproveedor = $request->idproveedor;
            $ingreso->idusuario = \Auth::user()->id;
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante; 
            $ingreso->num_comprobante = $request->num_comprobante; 
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto; 
            $ingreso->total = $request->total; 
            $ingreso->estado = 'Registrado'; 
            $ingreso->save();

            $detalles = $request->data;
            foreach ($detalles as $key => $det) {

                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();

            }
            
            \DB::commit();

        }catch(Exception $e) {
            \DB::rollBack();
        }

        
    }


    public function desactivar(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id); 
        $ingreso->ingreso = 'Anulado';
        $ingreso->save();
    }
}
