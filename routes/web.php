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

use App\Http\Controllers\System\Auth\AuthenticatedSessionController;

Route::middleware(["web"])
     ->group(function() {

        Route::middleware('guest')->group(function() {

            Route::get('login',  [AuthenticatedSessionController::class, 'create'])->name('login');
            Route::post('login', [AuthenticatedSessionController::class, 'store']);

        });

        Route::middleware('auth')->group(function () {

            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                 ->name('logout');

        });

        Route::middleware(['auth', 'verified'])->group(function () {

            // $rutaDefecto = __DIR__.'/System';

            // Route::prefix('/items')->group($rutaDefecto.'/Item.php');

        });

  });
