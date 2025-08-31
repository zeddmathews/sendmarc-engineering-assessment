<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\GameStoreRequest;
use App\Http\Requests\Game\GameUpdateRequest;
use App\Models\Game;
use App\Http\Resources\GameResource;
use App\Actions\Game\StartGame;
use Illuminate\Http\Request;
use App\Enums\MatchStatus;

class GameController extends Controller
{
    public function index()
    {
        return GameResource::collection(
            Game::with(['player1.user', 'player2.user', 'winner'])->get()
        );
    }

    public function show(Game $game)
    {
        return new GameResource($game->load(['player1.user', 'player2.user', 'winner']));
    }

    public function store(GameStoreRequest $request)
    {
        $game = Game::create($request->validated());
        return new GameResource($game->load(['player1.user', 'player2.user']));
    }

    public function update(GameUpdateRequest $request, Game $game)
    {
        $game->update($request->validated());
        return new GameResource($game->fresh()->load(['player1.user', 'player2.user']));
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return response()->noContent();
    }

    public function start(Game $game, StartGame $action)
    {
        $action->handle($game);
        return new GameResource($game->fresh()->load(['player1.user', 'player2.user']));
    }

    public function upcomingGames()
    {
        return GameResource::collection(
            Game::where('match_status', MatchStatus::Upcoming->value)
                ->orderBy('played_at', 'asc')
                ->take(5)
                ->with(['player1.user', 'player2.user'])
                ->get()
        );
    }
}

