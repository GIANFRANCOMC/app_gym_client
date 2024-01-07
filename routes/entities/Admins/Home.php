<?php

use App\Http\Controllers\Admins\{HomeController};
use Illuminate\Support\Facades\Route;

$entity = "home";

Route::get('',           [HomeController::class, 'index'])->name("$entity.index");
Route::get('/list',      [HomeController::class, 'list'])->name("$entity.list");
Route::get('/create',    [HomeController::class, 'create'])->name("$entity.create");
Route::post('',          [HomeController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit', [HomeController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',      [HomeController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',    [HomeController::class, 'update'])->name("$entity.update");
