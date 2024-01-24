<?php

use App\Http\Controllers\Admins\{SaleDetailController};
use Illuminate\Support\Facades\Route;

$entity = "saleDetails";

Route::get('',            [SaleDetailController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [SaleDetailController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [SaleDetailController::class, 'list'])->name("$entity.list");
Route::get('/create',     [SaleDetailController::class, 'create'])->name("$entity.create");
Route::post('',           [SaleDetailController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [SaleDetailController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [SaleDetailController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [SaleDetailController::class, 'update'])->name("$entity.update");

