<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PlayerController extends Controller
{
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

        if (!$request->user()->is_admin) {
            $data['email'] = $request->user()->email;
        }

        $data['user_id'] = $request->user()->id;

        Player::create($data);

        return to_route('dashboard');
    }

    public function show(Player $player)
    {
        return response()->json($player);
    }

    public function edit(Player $player)
    {
        //
    }

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

    public function destroy(Player $player)
    {
        $player->delete();
        return redirect()->route('players.index')->with('message', 'Player deleted successfully.');

    }
}
