<?php

use App\Http\Controllers\SelfCheckerController;
use Illuminate\Support\Facades\Route;

Route::get('/data-to-pdf', [SelfCheckerController::class, 'handle'])->name('dataToPdf');
