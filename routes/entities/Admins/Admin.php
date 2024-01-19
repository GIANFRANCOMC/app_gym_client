<?php

use App\Http\Controllers\Admins\{AdminController};
use Illuminate\Support\Facades\Route;

$entity = "admins";

Route::get('',            [AdminController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [AdminController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [AdminController::class, 'list'])->name("$entity.list");
Route::get('/create',     [AdminController::class, 'create'])->name("$entity.create");
Route::post('',           [AdminController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [AdminController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [AdminController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [AdminController::class, 'update'])->name("$entity.update");
