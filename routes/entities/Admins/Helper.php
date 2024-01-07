<?php

use App\Http\Controllers\Admins\{HelperController};
use Illuminate\Support\Facades\Route;

Route::get('/consultNumberDocument',      [HelperController::class, 'consultNumberDocument'])->name('helpers.consultNumberDocument');
