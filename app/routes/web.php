<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PlayerController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/simulate', fn () => Inertia::render('SimulateMatch'))->name('simulate');
    Route::get('/matches', fn () => Inertia::render('MatchHistory'))->name('matches');
    Route::get('/rank', fn () => Inertia::render('Rankings'))->name('rank');
    Route::get('/players', [PlayerController::class, 'indexPage'])->name('players.index');
    Route::get('/players/create', [PlayerController::class, 'createPage'])->name('players.create');
    Route::get('/players/{player}/edit', [PlayerController::class, 'editPage'])->name('players.edit');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
