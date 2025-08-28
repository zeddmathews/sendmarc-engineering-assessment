<?php

namespace App\Http\Controllers\Web;

use App\Models\Game;
use App\Models\Player;
use App\Actions\Game\AssignPoint;
use App\Actions\Game\EndGame;
use App\Http\Requests\PointAssignRequest;
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

    public function point(PointAssignRequest $request, Game $game, AssignPoint $assignPoint, EndGame $endGame)
    {
        $this->authorize('update', $game);

        $player = Player::findOrFail($request->player_id);

        $assignPoint->handle($game, $player);

        if ($game->winner_id) {
            $endGame->handle($game);
        }

        return response()->json(
            $game->fresh()->load(['player1', 'player2', 'winner'])
        );
    }
}
