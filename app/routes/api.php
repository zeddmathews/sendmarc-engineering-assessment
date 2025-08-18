<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TennisGameController;
use App\Http\Controllers\UserController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::apiResource('players', PlayerController::class)->names([
        'index'   => 'api.players.index',
        'store'   => 'api.players.store',
        'show'    => 'api.players.show',
        'update'  => 'api.players.update',
        'destroy' => 'api.players.destroy',
    ]);

    Route::get('games', [GameController::class, 'index'])->name('api.games.index');
    Route::get('games/upcomings', [GameController::class, 'upcomingGames'])->name('api.games.upcomings');
    Route::post('games/{game}/start', [GameController::class, 'start'])->name('api.games.start');
    Route::get('games/{game}', [GameController::class, 'show'])->name('api.games.show');

    Route::get('simulate-match/{game}', [TennisGameController::class, 'show'])->name('api.simulate.show');
    Route::post('simulate-match/{game}/point', [TennisGameController::class, 'point'])->name('api.sumlate.point');

    Route::middleware(['admin'])->group(function () {
        Route::post('games', [GameController::class, 'store'])->name('api.games.store');
        Route::put('games/{game}', [GameController::class, 'update'])->name('api.games.update');
        Route::delete('games/{game}', [GameController::class, 'destroy'])->name('api.games.destroy');
        Route::get('users', [UserController::class, 'index'])->name('api.users.index');
    });
});
