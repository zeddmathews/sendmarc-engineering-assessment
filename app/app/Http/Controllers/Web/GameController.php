<?php

namespace App\Http\Controllers\Web;

use App\Models\Game;
use App\Models\Player;
use Inertia\Inertia;
use App\Http\Controllers\Controller;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with(['player1', 'player2', 'winner'])->latest()->get();

        return Inertia::render('games/Index', [
            'games' => $games,
        ]);
    }

    public function create()
    {
        $players = Player::all();

        return Inertia::render('games/Create', [
            'players' => $players,
        ]);
    }

    public function edit(Game $game)
    {
        $game->load(['player1', 'player2']);
        $players = Player::all();
        return Inertia::render('games/Edit', [
            'game' => $game,
            'players' => $players,
        ]);
    }

    public function show(Game $game)
    {
        $game->load(['player1', 'player2', 'winner']);

        return Inertia::render('games/Show', [
            'game' => $game,
        ]);
    }
}
