<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Http\Resources\PlayerResource;
use App\Http\Requests\Player\PlayerStoreRequest;
use App\Http\Requests\Player\PlayerUpdateRequest;
use App\Services\PlayerService;

class PlayerController extends Controller
{
    protected PlayerService $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $this->authorize('viewAny', Player::class);
        $players = Player::with('user')
            ->visibleTo(auth()->user())
            ->get();

        return PlayerResource::collection($players);
    }

    public function show(Player $player)
    {
        $this->authorize('view', $player);
        return new PlayerResource($player->load('user'));
    }

    public function store(PlayerStoreRequest $request)
    {
        $this->authorize('create', Player::class);
        $player = $this->service->createPlayer(
            $request->validated(),
            $request->user()->is_admin
        );

        return new PlayerResource($player);
    }

    public function update(PlayerUpdateRequest $request, Player $player)
    {
        $this->authorize('update', $player);
        $player = $this->service->updatePlayer(
            $player,
            $request->validated(),
            $request->user()->is_admin
        );

        return new PlayerResource($player);
    }

    public function destroy(Player $player)
    {
        $this->authorize('delete', $player);
        $this->service->deletePlayer($player);

        return response()->noContent();
    }
}
