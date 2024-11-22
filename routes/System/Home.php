<?php

use App\Http\Controllers\System\{HomeController};
use Illuminate\Support\Facades\Route;

$entity = "home";

Route::get('',            [HomeController::class, 'index'])->name("$entity.index");
Route::get('/initParams', [HomeController::class, 'initParams'])->name("$entity.initParams");
Route::get('/initData',   [HomeController::class, 'initData'])->name("$entity.initData");
