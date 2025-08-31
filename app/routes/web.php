<?php

use App\Http\Controllers\Web\PlayerController;
use App\Http\Controllers\Web\GameController;
use App\Http\Controllers\Web\TennisGameController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

Route::middleware(['auth:sanctum', 'verified', 'player.exists'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/simulate', fn() => Inertia::render('SimulateMatch'))->name('simulate');
    Route::get('/matches', fn() => Inertia::render('MatchHistory'))->name('matches');
    Route::get('/rank', fn() => Inertia::render('Rankings'))->name('rank');
    Route::get('/players', [PlayerController::class, 'indexPage'])->name('players.index');
    Route::get('/players/{player}/edit', [PlayerController::class, 'editPage'])->name('players.edit');
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/upcomings', fn() => Inertia::render('games/Upcomings'))->name('games.upcomings');
    Route::put('players/{player}', [PlayerController::class, 'update'])->name('players.update');
});

Route::middleware(['auth:sanctum', 'admin', 'verified'])->group(function () {
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::get('/play/{game}', [TennisGameController::class, 'show'])->name('play.show');
    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');
    Route::delete('/players/{player}', [PlayerController::class, 'destroy'])->name('players.destroy');
    Route::post('/play/{game}/point', [TennisGameController::class, 'point'])->name('play.point');
});

Route::middleware(['auth:sanctum', 'verified'])
    ->withoutMiddleware('player.exists')
    ->group(function () {
        Route::get('/players/create', [PlayerController::class, 'createPage'])->name('players.create');
        Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
