<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Http\Requests\Game\GameStoreRequest;
use App\Http\Requests\Game\GameUpdateRequest;
use App\Http\Resources\GameResource;
use App\Services\GameService;
use App\Enums\MatchStatus;
use App\Http\Requests\Game\PointAssignRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with(['player1.user', 'player2.user']);

        if ($request->filled('game_id')) {
            $query->where('id', $request->input('game_id'));
        }
        if ($request->filled('player1_id')) {
            $query->where('player1_id', $request->input('player1_id'));
        }
        if ($request->filled('player2_id')) {
            $query->where('player2_id', $request->input('player2_id'));
        }

        $games = $query->latest()->get();

        if ($request->wantsJson()) {
            return GameResource::collection($games);
        }

        return Inertia::render('games/Index', [
            'games' => $games,
        ]);
    }

    public function create()
    {
        return Inertia::render('games/Create', [
            'players' => Player::all(),
        ]);
    }

    public function store(GameStoreRequest $request)
    {
        $game = Game::create($request->validated());
        $service = new GameService($game);

        if ($request->wantsJson()) {
            return new GameResource($game->fresh()->load(['player1.user', 'player2.user']));
        }

        return redirect()->route('games.index')
            ->with('success', 'Game created successfully.');
    }

    public function edit(Game $game)
    {
        $game->load(['player1', 'player2']);

        return Inertia::render('games/Edit', [
            'game' => $game,
            'players' => Player::all(),
        ]);
    }

    public function update(GameUpdateRequest $request, Game $game)
    {
        $service = new GameService($game);
        $service->update($request->validated());

        $game->load(['player1.user', 'player2.user']);

        if ($request->wantsJson()) {
            return new GameResource($game);
        }

        return redirect()->route('games.index')
            ->with('success', 'Game updated successfully.');
    }

    public function destroy(Request $request, Game $game)
    {
        $service = new GameService($game);
        $service->destroy();

        return redirect()->route('games.index')
            ->with('success', 'Game deleted successfully.');
    }

    public function show(Request $request, Game $game)
    {
        $game->load(['player1.user', 'player2.user', 'winner']);

        if ($request->wantsJson()) {
            return new GameResource($game);
        }

        return Inertia::render('games/Show', [
            'game' => $game,
        ]);
    }

    public function start(Request $request, Game $game)
    {
        $service = new GameService($game);
        $service->start();

        $game->load(['player1.user', 'player2.user']);

        if ($request->wantsJson()) {
            return new GameResource($game);
        }

        return redirect()->route('play.show', $game)
            ->with('success', 'Game started successfully.');
    }

    public function assignPoint(PointAssignRequest $request, Game $game)
    {
        $player = Player::findOrFail($request->input('player_id'));
        $service = new GameService($game);
        $service->assignPoint($player);

        $game->load(['player1.user', 'player2.user', 'winner']);

        if ($request->wantsJson()) {
            return new GameResource($game);
        }

        return redirect()->route('play.show', $game)
            ->with('success', 'Point assigned successfully.');
    }

    public function end(Request $request, Game $game)
    {
        $service = new GameService($game);
        $service->end();

        $game->load(['player1.user', 'player2.user', 'winner']);

        if ($request->wantsJson()) {
            return new GameResource($game);
        }

        return redirect()->route('games.index')
            ->with('success', 'Game ended successfully.');
    }


    public function simulate(Request $request)
    {
        $games = Game::where('match_status', MatchStatus::Upcoming->value)
            ->orderBy('played_at', 'asc')
            ->with(['player1.user', 'player2.user'])
            ->get();

        if ($request->wantsJson()) {
            return GameResource::collection($games);
        }

        return Inertia::render('SimulateMatch', [
            'games' => $games,
        ]);
    }
}
