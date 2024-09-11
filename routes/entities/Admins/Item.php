<?php

use App\Http\Controllers\Admins\{ItemController};
use Illuminate\Support\Facades\Route;

$entity = "items";

Route::get('',            [ItemController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [ItemController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [ItemController::class, 'list'])->name("$entity.list");
Route::get('/create',     [ItemController::class, 'create'])->name("$entity.create");
Route::post('',           [ItemController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [ItemController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [ItemController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [ItemController::class, 'update'])->name("$entity.update");

