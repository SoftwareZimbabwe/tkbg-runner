<?php

use App\Http\Controllers\BackgroundJobController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard/jobs', [BackgroundJobController::class, 'index'])->name('dashboard.jobs');
Route::post('/dashboard/jobs/{id}/cancel', [BackgroundJobController::class, 'cancel'])->name('dashboard.jobs.cancel');
