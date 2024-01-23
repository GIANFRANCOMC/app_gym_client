<?php

use App\Http\Controllers\Admins\{ProductServiceController};
use Illuminate\Support\Facades\Route;

$entity = "productServices";

Route::get('',            [ProductServiceController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [ProductServiceController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [ProductServiceController::class, 'list'])->name("$entity.list");
Route::get('/create',     [ProductServiceController::class, 'create'])->name("$entity.create");
Route::post('',           [ProductServiceController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [ProductServiceController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [ProductServiceController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [ProductServiceController::class, 'update'])->name("$entity.update");

