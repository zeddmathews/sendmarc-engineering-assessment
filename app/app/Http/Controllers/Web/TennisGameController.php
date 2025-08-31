<?php

namespace App\Http\Controllers\Web;

use App\Models\Game;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class TennisGameController extends Controller
{
    public function show(Game $game)
    {
        $game->load(['player1', 'player2', 'winner']);

        return Inertia::render('games/Show', [
            'game' => $game,
        ]);
    }
}
