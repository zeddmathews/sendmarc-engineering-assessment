<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

Route::middleware(['auth:sanctum', 'verified', 'player.exists'])->group(function () {
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/simulate', [GameController::class, 'simulate'])->name('simulate');
    Route::get('/matches', fn() => Inertia::render('MatchHistory'))->name('matches');
    Route::get('/rank', fn() => Inertia::render('Rankings'))->name('rank');

    Route::get('/players', [PlayerController::class, 'indexPage'])->name('players.index');
    Route::get('/players/{player}/edit', [PlayerController::class, 'editPage'])->name('players.edit');
    Route::put('/players/{player}', [PlayerController::class, 'updateWeb'])->name('players.update');

    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/upcoming', fn() => [GameController::class, 'upcomingGames'])->name('games.upcoming');
});

Route::middleware(['auth:sanctum', 'admin', 'verified'])->group(function () {
    Route::get('/games/create', [GameController::class, 'create'])->name('games.create');
    Route::get('/games/{game}/edit', [GameController::class, 'edit'])->name('games.edit');
    Route::get('/play/{game}', [GameController::class, 'show'])->name('play.show'); // Play page

    Route::post('/games', [GameController::class, 'store'])->name('games.store');
    Route::put('/games/{game}', [GameController::class, 'update'])->name('games.update');
    Route::delete('/games/{game}', [GameController::class, 'destroy'])->name('games.destroy');

    Route::post('/games/{game}/start', [GameController::class, 'start'])->name('games.start');
    Route::post('/play/{game}/point', [GameController::class, 'assignPoint'])->name('play.point');
    Route::post('/games/{game}/end', [GameController::class, 'end'])->name('games.end');

    Route::delete('/players/{player}', [PlayerController::class, 'destroyWeb'])->name('players.destroy');
});

Route::middleware(['auth:sanctum', 'verified'])
    ->withoutMiddleware('player.exists')
    ->group(function () {
        Route::get('/players/create', [PlayerController::class, 'createPage'])->name('players.create');
        Route::post('/players', [PlayerController::class, 'storeWeb'])->name('players.store');
    });

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
