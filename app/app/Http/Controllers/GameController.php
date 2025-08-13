<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with(['player1', 'player2'])->orderBy('played_at', 'desc');
        if ($request->has('game_id')) {
            $query->where('id', $request->game_id);
        }
        if ($request->has('player1_id')) {
            $query->where('player1_id', $request->player1_id);
        }
        if ($request->has('player2_id')) {
            $query->where('player2_id', $request->player2_id);
        }
        $games = $query->get();

        return Inertia::render('games/Index', [
            'games' => $games
        ]);
    }

    public function create()
    {
        return Inertia::render('games/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'played_at' => 'required|date',
            'winner_id' => 'nullable|exists:players,id',
            'match_status' => 'required|string|in:upcoming,ongoing,completed,cancelled,tied',
            'player1_id' => 'nullable|exists:players,id',
            'player2_id' => 'nullable|exists:players,id',
        ]);

        $game = Game::create($data);

        return redirect()->route('games.index')->with('success', 'Game created successfully.');
    }

    public function show(Game $game)
    {
        $game->load(['player1', 'player2']);

        return Inertia::render('games/Show', [
            'game' => $game
        ]);
    }

    public function edit(Game $game)
    {
        $game->load(['player1', 'player2']);
        return Inertia::render('games/Edit', [
            'game' => $game
        ]);
    }

    public function update(Request $request, Game $game)
    {
        $data = $request->validate([
            'played_at' => 'required|date',
            'winner_id' => 'nullable|exists:players,id',
            'match_status' => 'required|string|in:upcoming,ongoing,completed,cancelled,tied',
            'player1_id' => 'nullable|exists:players,id',
            'player2_id' => 'nullable|exists:players,id',
        ]);

        $game->update($data);

        return redirect()->route('games.index')->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')->with('success', 'Game deleted successfully.');
    }
}
