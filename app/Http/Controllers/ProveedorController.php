<?php
namespace SisFramework\Http\Controllers;

use Illuminate\Http\Request;
use SisFramework\Persona;
use Illuminate\Support\Facades\Redirect; //Libreria para hacer redireccion
use Illuminate\Support\Facades\input;
use SisFramework\Http\Requests\PersonaFormRequest;
use DB;

class ProveedorController extends Controller {

    public function index(Request $request) {
        $query = trim($request->get('searchText'));
        $personas = DB::table('persona')
                ->where('nombre', 'LIKE', '%' . $query . '%')
                ->where('tipo_persona', '=', 'Proveedor')
                ->orwhere('num_documento', 'LIKE', '%' . $query . '%')
                ->where('tipo_persona', '=', 'Proveedor')
                ->orderBy('idpersona', 'desc')
                ->paginate(7);
        return view('compras.proveedor.index', ["personas" => $personas, "searchText" => $query]);
    }

    public function create() {
        return view("compras.proveedor.create");
    }

    public function store(Request $request) {
        $persona = new Persona;
        $persona->tipo_persona = 'Proveedor';
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request->get('num_documento');
        $persona->direccion = $request->get('direccion');
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->save();
        return Redirect::to('compras/proveedor');
    }

    public function show($id) {
        return view("compras/proveedor.show", ["personas" => Persona::findOrFail($id)]);
    }

    public function edit($id) {
        return view("compras/proveedor.edit", ["personas" => Persona::findOrFail($id)]);
    }

    public function update(Request $request, $id) {
        $persona = Persona::findOrFail($id);
        $persona->nombre = $request->get('nombre');
        $persona->tipo_documento = $request->get('tipo_documento');
        $persona->num_documento = $request->get('num_documento');
        $persona->direccion = $request->get('direccion');
        $persona->telefono = $request->get('telefono');
        $persona->email = $request->get('email');
        $persona->update();
        return Redirect::to('compras/proveedor');
    }

    public function destroy($id) {
        $persona = Persona::findOrFail($id);
        $persona->tipo_persona = 'Inactivo';
        return Redirect::to('compras/proveedor');
    }

}
