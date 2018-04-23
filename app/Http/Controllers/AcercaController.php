<?php

namespace SisFramework\Http\Controllers;

use SisFramework\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use SisFramework\Http\Requests\CategoriaFormRequest;
use DB;

class AcercaController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $titulo = "MaeDWEB | Maestría en Desarrollo Web";
        $curso = "Framework";
        $docente = "Aaron Díaz";
        $alumno = "Juan Carlos Ortube Lahor";
        return view('acerca.index', ["titulo" => $titulo, "curso" => $curso, "docente" => $docente, "alumno" => $alumno]);
    }



}
