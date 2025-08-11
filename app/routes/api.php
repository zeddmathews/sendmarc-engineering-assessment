<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::apiResource('players', PlayerController::class)->names([
        'index' => 'api.players.index',
        'create'  => 'api.players.create',
        'store'   => 'api.players.store',
        'show'    => 'api.players.show',
        'edit'    => 'api.players.edit',
        'update'  => 'api.players.update',
        'destroy' => 'api.players.destroy',
    ]);
    Route::apiResource('games', 'App\Http\Controllers\GameController')->names([
        'index' => 'api.games.index',
        'create'  => 'api.games.create',
        'store'   => 'api.games.store',
        'show'    => 'api.games.show',
        'edit'    => 'api.games.edit',
        'update'  => 'api.games.update',
        'destroy' => 'api.games.destroy',
    ]);
});
