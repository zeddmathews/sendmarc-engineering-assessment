<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameStoreRequest;
use App\Http\Requests\Game\GameUpdateRequest;
use App\Models\Game;
use App\Actions\Games\StartGame;
use Illuminate\Http\Request;
use App\Enums\MatchStatus;

class GameController extends Controller
{
    public function index()
    {
        return Game::with(['player1', 'player2'])->get();
    }

    public function store(GameStoreRequest $request, StartGame $startGame)
    {
        $game = Game::create($request->validated());
        return response()->json($game, 201);
    }

    public function update(GameUpdateRequest $request, Game $game)
    {
        $game->update($request->validated());
        return response()->json($game);
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return response()->noContent();
    }

    public function start(Game $game, StartGame $action)
    {
        $action->handle($game);
        return response()->json($game->fresh());
    }

    public function upcomingGames()
    {
        return Game::where('match_status', MatchStatus::Upcoming->value)
            ->orderBy('played_at', 'asc')
            ->take(5)
            ->get();
    }
}

