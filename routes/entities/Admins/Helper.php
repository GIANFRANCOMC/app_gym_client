<?php

use App\Http\Controllers\Admins\{HelperController};
use Illuminate\Support\Facades\Route;

$entity = "helpers";

Route::get('/consultNumberDocument', [HelperController::class, 'consultNumberDocument'])->name("$entity.consultNumberDocument");
