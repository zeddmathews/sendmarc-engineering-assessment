<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::apiResource('players', PlayerController::class)->names([
        'index' => 'api.players.index',
        'create'  => 'api.players.create',
        'store'   => 'api.players.store',
        'show'    => 'api.players.show',
        'edit'    => 'api.players.edit',
        'update'  => 'api.players.update',
        'destroy' => 'api.players.destroy',
    ]);
});
