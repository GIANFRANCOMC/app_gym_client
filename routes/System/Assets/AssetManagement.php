<?php

use App\Http\Controllers\System\Assets\{AssetManagementController};
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

Route::post('assignAssetToBranch',     [AssetManagementController::class, 'assignAssetToBranch'])->name("$entity.assignAssetToBranch");
Route::post('unassignAssetFromBranch', [AssetManagementController::class, 'unassignAssetFromBranch'])->name("$entity.unassignAssetFromBranch");
Route::patch('assign/{id}',            [AssetManagementController::class, 'assign'])->name("$entity.assign");
Route::patch('assignToUser/{id}',      [AssetManagementController::class, 'assignToUser'])->name("$entity.assignToUser");
