<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/hola', function () {
    return 'hola mundo';
});
Route::get('/objetos/caracteristicas', function () {
    return 'el id del objeto es:'.$_GET['id'];
});
Route::get('/objeto/{id}', function ($id) {
    return "el id del objeto es:{$id}";
})->where('id','[0-9]+');
Route::get('/objeto/nuevo', function () {
    return "crear nuevo objeto";
});
//aliasp? puede o no puede existir 
Route::get('/alias/{nombrep}/{aliasp?}', function ($nombrep,$aliasp=null) {
    if($aliasp){
        return "El producto {$nombrep}, tiene el alias de {$aliasp}";
    }else{
    return "El producto {$nombrep}, no tiene el alias ";
    }
});
Route::resource('almacen/categoria','CategoriaController');
Route::resource('almacen/articulo','ArticuloController');
Route::resource('ventas/cliente','ClienteController');
Route::resource('compras/proveedor','ProveedorController');
Route::resource('compras/ingreso','IngresoController');
Route::resource('ventas/venta','VentaController');