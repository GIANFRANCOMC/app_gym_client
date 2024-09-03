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

foreach (config('tenancy.central_domains') as $domain) {

    Route::domain($domain)->group(function () {

        Route::get('/migrate-seed', function () {

            // Ejecutar migraciones y seeders
            Artisan::call('migrate --seed');

            // Devuelve la salida del comando Artisan
            return Artisan::output();

        });

    });
}
