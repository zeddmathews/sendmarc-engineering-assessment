<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\GameController;
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
    Route::get('games/upcoming', [GameController::class, 'upcomingGames'])->name('api.games.upcoming');
    Route::get('games/{game}', [GameController::class, 'show'])->name('api.games.show');

    Route::middleware(['admin'])->group(function () {
        Route::post('games', [GameController::class, 'store'])->name('api.games.store');
        Route::put('games/{game}', [GameController::class, 'update'])->name('api.games.update');

        Route::post('games/{game}/start', [GameController::class, 'start'])->name('api.games.start');
        Route::post('games/{game}/point', [GameController::class, 'assignPoint'])->name('api.games.assignPoint');
        Route::post('games/{game}/end', [GameController::class, 'end'])->name('api.games.end');

        Route::get('games/{game}/play', [GameController::class, 'show'])->name('api.games.play');

        Route::get('users', [UserController::class, 'index'])->name('api.users.index');
    });
});
