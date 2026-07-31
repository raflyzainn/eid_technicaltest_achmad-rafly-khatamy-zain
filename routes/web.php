<?php

use App\Http\Controllers\ReportExportController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('machines', 'machines')
    ->middleware(['auth', 'admin'])
    ->name('machines');

Route::view('reports', 'reports')
    ->middleware(['auth'])
    ->name('reports');

Route::get('reports/export', ReportExportController::class)
    ->middleware(['auth'])
    ->name('reports.export');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
