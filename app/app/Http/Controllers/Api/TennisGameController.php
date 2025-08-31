<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Game\AssignPoint;
use App\Actions\Game\EndGame;
use App\Models\Game;
use App\Models\Player;
use App\Http\Resources\GameResource;

class TennisGameController extends Controller
{
    public function serialize(Game $game)
    {
        return new GameResource($game->load(['player1.user', 'player2.user', 'winner']));
    }

    public function assignPoint(Game $game, AssignPoint $assignPoint, EndGame $endGame)
    {
        $player = Player::findOrFail(request('player'));
        $assignPoint->handle($game, $player);

        if ($game->winner_id) {
            $endGame->handle($game);
        }

        return new GameResource($game->fresh()->load(['player1.user', 'player2.user', 'winner']));
    }
}
