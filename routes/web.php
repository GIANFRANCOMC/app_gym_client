<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\CustomerController;

/* // Rutas para listar todos los clientes
Route::get('/customers', [CustomerController::class, 'index']);

// Ruta para mostrar el formulario de creación de un nuevo cliente
Route::get('/customers/create', [CustomerController::class, 'create']);

// Ruta para almacenar un nuevo cliente en la base de datos
Route::post('/customers', [CustomerController::class, 'store']);

// Rutas para mostrar, actualizar y eliminar un cliente específico
Route::get('/customers/{customer}', [CustomerController::class, 'show']);
Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit']);
Route::put('/customers/{customer}', [CustomerController::class, 'update']);
Route::delete('/customers/{customer}', [CustomerController::class, 'destroy']); */

//Route::resource('customers', CustomerController::class);

Route::resource('customers', CustomerController::class)->names([
    'index' => 'customers.index',
    'create' => 'customers.create',
    'store' => 'customers.store',
    'show' => 'customers.show',
    'edit' => 'customers.edit',
    'update' => 'customers.update',
    'destroy' => 'customers.destroy',
]);
