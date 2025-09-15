<?php

// routes/web.php
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::post('/log', [DashboardController::class, 'store'])->name('log.store');
Route::get('/export', [DashboardController::class, 'export'])->name('log.export');
