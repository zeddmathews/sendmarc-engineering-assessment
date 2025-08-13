<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/simulate', fn() => Inertia::render('SimulateMatch'))->name('simulate');
    Route::get('/matches', fn() => Inertia::render('MatchHistory'))->name('matches');
    Route::get('/rank', fn() => Inertia::render('Rankings'))->name('rank');

    Route::get('/players', [PlayerController::class, 'indexPage'])->name('players.index');
    Route::get('/players/create', [PlayerController::class, 'createPage'])->name('players.create');
    Route::get('/players/{player}/edit', [PlayerController::class, 'editPage'])->name('players.edit');

    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/upcomings', fn() => Inertia::render('games/Upcomings'))->name('games.upcomings');
});

Route::middleware(['auth:sanctum', 'admin', 'verified'])->group(function () {
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
