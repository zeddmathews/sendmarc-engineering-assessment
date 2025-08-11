<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        return $user->isAdmin() ? Inertia::render('game/Create') : abort(403, 'Unauthorized');
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return response()->json($game, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        $game->load(['player1', 'player2']);
        return response()->json($game);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
