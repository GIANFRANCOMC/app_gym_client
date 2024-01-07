<?php

use App\Http\Controllers\Admins\{CustomerController};
use Illuminate\Support\Facades\Route;

$entity = "customers";

Route::get('',           [CustomerController::class, 'index'])->name("$entity.index");
Route::get('/list',      [CustomerController::class, 'list'])->name("$entity.list");
Route::get('/create',    [CustomerController::class, 'create'])->name("$entity.create");
Route::post('',          [CustomerController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',      [CustomerController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',    [CustomerController::class, 'update'])->name("$entity.update");

