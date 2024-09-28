<?php

use App\Http\Controllers\System\{ExportController};
use Illuminate\Support\Facades\Route;

$entity = "exports";

Route::get('',            [ExportController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [ExportController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [ExportController::class, 'list'])->name("$entity.list");
Route::get('/create',     [ExportController::class, 'create'])->name("$entity.create");
Route::post('',           [ExportController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [ExportController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [ExportController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [ExportController::class, 'update'])->name("$entity.update");

