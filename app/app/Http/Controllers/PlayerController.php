<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Requests\Player\PlayerStoreRequest;
use App\Http\Requests\Player\PlayerUpdateRequest;
use App\Http\Resources\PlayerResource;
use App\Services\PlayerService;

class PlayerController extends Controller
{
    protected PlayerService $service;

    public function __construct(PlayerService $service)
    {
        $this->service = $service;
    }

    public function indexPage()
    {
        return Inertia::render('players/Index', [
            'players' => PlayerResource::collection(
                Player::with('user')->get()
            )->resolve(),
        ]);
    }

    public function createPage()
    {
        $users = auth()->user()->is_admin
            ? User::where('is_admin', false)
                ->whereDoesntHave('player')
                ->select('id', 'name', 'email')
                ->get()
            : collect();

        return Inertia::render('players/Create', [
            'users' => $users,
        ]);
    }

    public function editPage(Player $player)
    {
        return Inertia::render('players/Edit', [
            'player' => (new PlayerResource($player->load('user')))->resolve(),
            'is_admin' => auth()->user()->is_admin,
        ]);
    }

    public function storeWeb(PlayerStoreRequest $request)
    {
        $this->authorize('create', Player::class);

        $this->service->create(
            $request->validated(),
            $request->user()->is_admin
        );

        return redirect()->route('players.index')
            ->with('success', 'Player created successfully.');
    }

    public function updateWeb(PlayerUpdateRequest $request, Player $player)
    {
        $this->authorize('update', $player);

        $this->service->update(
            $player,
            $request->validated(),
            $request->user()->is_admin
        );

        return redirect()->route('players.index')
            ->with('success', 'Player updated successfully.');
    }

    public function destroyWeb(Player $player)
    {
        $this->authorize('delete', $player);

        $this->service->delete($player);

        return redirect()->route('players.index')
            ->with('success', 'Player deleted successfully.');
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

        $player = $this->service->create(
            $request->validated(),
            $request->user()->is_admin
        );

        return (new PlayerResource($player))->response()->setStatusCode(201);
    }

    public function update(PlayerUpdateRequest $request, Player $player)
    {
        $this->authorize('update', $player);

        $player = $this->service->update(
            $player,
            $request->validated(),
            $request->user()->is_admin
        );

        return new PlayerResource($player);
    }

    public function destroy(Player $player)
    {
        $this->authorize('delete', $player);

        $this->service->delete($player);

        return response()->noContent();
    }
}
