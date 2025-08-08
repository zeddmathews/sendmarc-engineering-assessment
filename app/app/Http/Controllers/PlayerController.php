<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Player::all());
    }
    public function indexPage()
    {
        return Inertia::render('players/Index');
    }

    public function createPage()
    {
        return Inertia::render('players/Create');
    }

    public function editPage(Player $player)
    {
        return Inertia::render('players/Edit', ['player' => $player]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:players,email',
            'rank' => ['nullable', Rule::in(['Silver', 'Gold', 'Platinum'])],
            'country' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
        ]);

        $player = Player::create($data);

        return response()->json($player, 201);
    }

    /**
     * Display the specified resource.
    */
    public function show(Player $player)
    {
        return response()->json($player);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $data = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('players')->ignore($player->id)],
            'rank' => ['nullable', Rule::in(['Silver', 'Gold', 'Platinum'])],
            'country' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
        ]);

        $player->update($data);

        return response()->json($player);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return response()->json(null, 204);
    }
}
