<?php

namespace SisFramework\Http\Controllers;

use Illuminate\Http\Request;
use SisFramework\User;
use SisFramework\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use DB;

class UsuarioController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        if ($request) {
            $query = trim($request->get('searchText'));
            $users = DB::table('users')->where('name', 'LIKE', '%' . $query . '%')
                    ->orderBy('id', 'desc')
                    ->paginate(7);
            return view('acceso/usuario.index', ["usuarios" => $users, "searchText" => $query]);
        }
    }

    public function create() {
        return view("acceso.usuario.create");
    }

    public function store(Request $request) {
        User::create([
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password')),
        ]);
        return Redirect::to('acceso/usuario');
    }

    public function show($id) {
        return view("acceso.usuario.show", ["usuarios" => User::findOrFail($id)]);
    }

    public function edit($id) {
        return view("acceso.usuario.edit", ["usuarios" => User::findOrFail($id)]);
    }

    public function update(Request $request, $id) {
        $users = User::findOrFail($id);
        $users->name = $request->get('name');
        $users->email = $request->get('email');
        $users->password = Hash::make($request->get('password'));
        $users->update();
        return Redirect::to('acceso/usuario');
    }

    public function destroy($id) {
        User::destroy($id);
        return Redirect::to('acceso/usuario');
    }

}
