<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin');

Route::get('games/{game}', [GameController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('games.show');

Route::get('results/{game}', [GameController::class, 'results'])
    ->middleware(['auth', 'verified'])
    ->name('results.show');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
