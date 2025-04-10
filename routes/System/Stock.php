<?php

use App\Http\Controllers\System\{StockController};
use Illuminate\Support\Facades\Route;

$entity = "stocks";

Route::get('',            [StockController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [StockController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [StockController::class, 'list'])->name("$entity.list");
Route::get('/create',     [StockController::class, 'create'])->name("$entity.create");
Route::post('',           [StockController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [StockController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [StockController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [StockController::class, 'update'])->name("$entity.update");
