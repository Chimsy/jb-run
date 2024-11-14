<?php

use App\Http\Controllers\BackgroundJobController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BackgroundJobController::class, 'index'])->name('background-jobs.index');
Route::post('/cancel/{id}', [BackgroundJobController::class, 'cancel'])->name('background-jobs.cancel');

