<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Game\AssignPoint;
use App\Actions\Game\EndGame;
use App\Models\Game;
use App\Models\Player;

class TennisGameController extends Controller
{
    public function serialize(Game $game)
    {
        return response()->json($game->load(['player1', 'player2', 'winner']));
    }

    public function assignPoint(Game $game, AssignPoint $assignPoint, EndGame $endGame)
    {
        $player = Player::findOrFail(request('player'));
        $assignPoint->handle($game, $player);

        if ($game->winner_id) {
            $endGame->handle($game);
        }
        return response()->json(
            $game->fresh()->load(['player1', 'player2', 'winner'])
        );
    }
}
