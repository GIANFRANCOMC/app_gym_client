<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    "web",
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {

    Route::get('/', function () {
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });

    //Route::middleware(['auth', 'verified'])->group(function () {

        $rutaDefecto = __DIR__.'/entities/Admins';

        Route::prefix('/admins')->group($rutaDefecto.'/Admin.php');
        Route::prefix('/branches')->group($rutaDefecto.'/Branch.php');
        Route::prefix('/customers')->group($rutaDefecto.'/Customer.php');
        Route::prefix('/helpers')->group($rutaDefecto.'/Helper.php');
        Route::prefix('/home')->group($rutaDefecto.'/Home.php');
        Route::prefix('/memberships')->group($rutaDefecto.'/Membership.php');
        Route::prefix('/productServices')->group($rutaDefecto.'/ProductService.php');
        Route::prefix('/sales')->group($rutaDefecto.'/Sale.php');
        Route::prefix('/saleDetails')->group($rutaDefecto.'/SaleDetail.php');

        require __DIR__.'/auth.php';

    //});

});
