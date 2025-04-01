<?php

use App\Http\Controllers\System\{HelperController};
use Illuminate\Support\Facades\Route;

$entity = "helpers";

Route::get('/searchDocumentNumber', [HelperController::class, 'searchDocumentNumber'])->name("$entity.searchDocumentNumber");
