<?php

use App\Http\Controllers\System\{AssetManagementController};
use Illuminate\Support\Facades\Route;

$entity = "assets_management";

Route::get('',            [AssetManagementController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [AssetManagementController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [AssetManagementController::class, 'list'])->name("$entity.list");
Route::get('/create',     [AssetManagementController::class, 'create'])->name("$entity.create");
Route::post('',           [AssetManagementController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [AssetManagementController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [AssetManagementController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [AssetManagementController::class, 'update'])->name("$entity.update");
