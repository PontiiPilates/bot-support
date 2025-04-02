<?php

use App\Http\Controllers\SelfCheckerController;
use Illuminate\Support\Facades\Route;

Route::get('/top-results', [SelfCheckerController::class, 'handle'])->name('topResults');