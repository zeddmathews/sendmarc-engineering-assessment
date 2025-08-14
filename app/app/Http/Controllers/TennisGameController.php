<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Services\TennisGameService;
use App\Jobs\UpdateGamesWon;
use Inertia\Inertia;

class TennisGameController extends Controller
{
    public function show(Game $game)
    {
        $game->load(['player1', 'player2']);

        $service = new TennisGameService($game);

        return response()->json($service->serialize());
    }

    public function point(Request $request, Game $game)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
        ]);

        $game->load(['player1', 'player2']);

        $player = Player::findOrFail($request->input('player_id'));

        $service = new TennisGameService($game);

        $service->pointFor($player);

        if ($service->isGameOver() && !$game->winner_id) {
            $winner = $service->winner();
            $game->winner_id = $winner->id;
            $game->match_status = 'completed';
            $game->save();

            UpdateGamesWon::dispatch($winner);
        }

        return response()->json($service->serialize());
    }
}
