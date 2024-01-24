<?php

use App\Http\Controllers\Admins\{MembershipController};
use Illuminate\Support\Facades\Route;

$entity = "memberships";

Route::get('',            [MembershipController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [MembershipController::class, 'initParams'])->name("$entity.initParams");
Route::get('/list',       [MembershipController::class, 'list'])->name("$entity.list");
Route::get('/create',     [MembershipController::class, 'create'])->name("$entity.create");
Route::post('',           [MembershipController::class, 'store'])->name("$entity.store");
Route::get('/{id}/edit',  [MembershipController::class, 'edit'])->name("$entity.edit");
Route::get('/{id}',       [MembershipController::class, 'show'])->name("$entity.show");
Route::patch('/{id}',     [MembershipController::class, 'update'])->name("$entity.update");

