<?php

use App\Http\Controllers\{CustomerController};
use Illuminate\Support\Facades\Route;

Route::get('',           [CustomerController::class, 'index'])->name('customers.index');
Route::get('/list',      [CustomerController::class, 'list'])->name('customers.list');
Route::get('/create',    [CustomerController::class, 'create'])->name('customers.create');
Route::post('',          [CustomerController::class, 'store'])->name('customers.store');
Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
Route::get('/{id}',      [CustomerController::class, 'show'])->name('customers.show');
Route::patch('/{id}',    [CustomerController::class, 'update'])->name('customers.update');
