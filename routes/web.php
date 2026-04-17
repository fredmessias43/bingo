<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DrawnNumberController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('admin');

Route::resource('games',GameController::class)
    ->middleware(['auth', 'verified', HandlePrecognitiveRequests::class]);

Route::get('results/{game}', [GameController::class, 'results'])
    ->middleware(['auth', 'verified'])
    ->name('results.show');

Route::prefix('games/{game}/drawn-numbers')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DrawnNumberController::class, 'index'])->name('games.drawn-numbers.index');
    Route::post('/', [DrawnNumberController::class, 'store'])->name('games.drawn-numbers.store');
    Route::delete('/{drawnNumber}', [DrawnNumberController::class, 'destroy'])->name('games.drawn-numbers.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
