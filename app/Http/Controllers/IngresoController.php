<?php

namespace SisFramework\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\input;
use SisFramework\Http\Request\IngresoFormRequest;
use SisFramework\Ingreso;
use SisFramework\DetalleIngreso;
use DB;
Use Carbon\Carbon;
use Response;
use Illuminate\Support\Collection;

class IngresoController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request) {
            $query = trim($request->get('searchText'));
            $ingresos = DB::table('ingreso as i')
                    ->join('persona as p', 'i.idproveedor', '=', 'p.idpersona')
                    ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
                    ->select('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra)as total'))
                    ->where('i.num_comprobante', 'LIKE', '%' . $query . '%')
                    ->orderBy('i.idingreso', 'desc')
                    ->groupBy('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
                    ->paginate(7);
            return view('compras.ingreso.index', ["ingresos" => $ingresos, "searchText" => $query]);
        }
        /*
         * Este metodo realiza consulta uniendo 3 tablas de la base de datos 
         * (ingreso, persona, detalle_ingreso) a travez del JOIN para 
         * mostrar en la vista COMPRAS->INGRESO->index.blade.php a travez de un grid.
        */
    }

    public function create() {
        $personas = DB::table('persona')->where('tipo_persona', '=', 'proveedor')->get();
        $articulos = DB::table('articulo as art')
                ->select(DB::raw('CONCAT(art.codigo, " ", art.nombre) as articulo'), 'art.idarticulo')
                ->where('art.estado', '=', 'Activo')
                ->get();
        return view('compras.ingreso.create', ["personas" => $personas, "articulos" => $articulos]);
    }

/* Este metodo llama al formulario para crear un nuevo ingreso de proveedores y articulos.
 * Obtiene a travez de la variable $personas todas las personas existentes que sean PROVEEDORES,
 * y tambien obtiene a travez de la variable $articulos todos los articulos disponibles que existen.
 * Estas dos varialbles las envia a la vista COMPRAS->INGRESO->create.blade.php para cargar en los select's 
 * correspondiente que son parte de la informacion que se necesita registrar las cantidades, precio de compra,
precio de venta de un articulo de un proveedor determinado */

    public function store(Request $request) {
        try {
            DB::beginTransaction();

            $ingreso = new Ingreso;
            $ingreso->idproveedor = $request->get('idproveedor');
            $ingreso->tipo_comprobante = $request->get('tipo_comprobante');
            $ingreso->serie_comprobante = $request->get('serie_comprobante');
            $ingreso->num_comprobante = $request->get('num_comprobante');
            $mytime = Carbon::now('America/La_Paz');
            $ingreso->fecha_hora = $mytime->ToDateTimeString();
            $ingreso->impuesto = '16';
            $ingreso->estado = 'A';
            $ingreso->save();

            $idarticulo = $request->get('idarticulo');
            $cantidad = $request->get('cantidad');
            $precio_compra = $request->get('precio_compra');
            $precio_venta = $request->get('precio_venta');

            $cont = 0;

            while ($cont < count($idarticulo)) {
                $detalle = new DetalleIngreso();
                $detalle->idingreso = $ingreso->idingreso;
                $detalle->idarticulo = $idarticulo[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio_compra = $precio_compra[$cont];
                $detalle->precio_venta = $precio_venta[$cont];
                $detalle->save();

                $cont = $cont + 1;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return Redirect::to('compras/ingreso');
    }

/* Este metodo ejecuta la insercion de datos enviados de COMPRAS->INGRESO->create.blade.php 
 * se ejecuta  beginTransaction() para el manejo manual de transacciones, 
 * Puede deshacer la transacción a través del metodo rollBack,
 * Por último, puede confirmar una transacción a través del metodo commit
 * lo utiliza a travez de una excepcion.
 * Realiza la insercion de un registro a la tabla INGRESO y usa un ciclo while para 
 * realizar la insercion de los registros a la tabla DETALLE_INGRESO todos con el id de INGRESO 
 * al que pertenecen. */

    public function show($id) {
        $ingreso = DB::table('ingreso as i')
                ->join('persona as p', 'i.idproveedor', '=', 'p.idpersona')
                ->join('detalle_ingreso as di', 'i.idingreso', '=', 'di.idingreso')
                ->select('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado', DB::raw('sum(di.cantidad*precio_compra)as total'))
                ->groupBy('i.idingreso', 'i.fecha_hora', 'p.nombre', 'i.tipo_comprobante', 'i.serie_comprobante', 'i.num_comprobante', 'i.impuesto', 'i.estado')
                ->where('i.idingreso', '=', $id)
                ->first();

        $detalles = DB::table('detalle_ingreso as d')
                        ->join('articulo as a', 'd.idarticulo', '=', 'a.idarticulo')
                        ->select('a.nombre as articulo', 'd.cantidad', 'd.precio_compra', 'd.precio_venta')
                        ->where('d.idingreso', '=', $id)->get();

        return view("compras.ingreso.show", ["ingreso" => $ingreso, "detalles" => $detalles]);
    }

/* Este metodo obtiene dos consultas una a traves de la variable $ingreso usa 3 tablas (INGRESO, PERSONA, DETALLE_INGRESO),
 * unidas a traves del uso del JOIN donde se recibe el id de ingreso que se desea obtener.
 * otra consulta a traves de la variable $detalles que realisa una consulta a la tabla DETALLE_INGRESO
 * para obtener todos los articulos, cantidades, precio de compra y venta del id de ingreso recibido 
 * estas consultas son enviadas a las vista COMPRAS->INGRESO->show.blade.php para ser mostrados.
 *  */

    public function destroy($id) {
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->estado = 'C';
        $ingreso->update();
        return Redirect::to('compras/ingreso');
    }

/* Este metodo realiza el cambio de estado de un id de la tabla INGRESO */
}
