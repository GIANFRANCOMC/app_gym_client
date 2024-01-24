<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


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

Route::get('/', function () {return view('test');});

Route::middleware(['auth', 'verified'])->group(function () {

    $rutaDefecto = __DIR__.'/entities/Admins';

    Route::prefix('/admins')->group($rutaDefecto.'/Admin.php');
    Route::prefix('/branches')->group($rutaDefecto.'/Branch.php');
    Route::prefix('/customers')->group($rutaDefecto.'/Customer.php');
    Route::prefix('/helpers')->group($rutaDefecto.'/Helper.php');
    Route::prefix('/home')->group($rutaDefecto.'/Home.php');
    Route::prefix('/memberships')->group($rutaDefecto.'/Membership.php');
    Route::prefix('/productServices')->group($rutaDefecto.'/ProductService.php');

});

Route::get('/migrate-seed', function () {

    // Ejecutar migraciones y seeders
    Artisan::call('migrate --seed');

    // Devuelve la salida del comando Artisan
    return Artisan::output();

});

require __DIR__.'/auth.php';

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
// crear guards
