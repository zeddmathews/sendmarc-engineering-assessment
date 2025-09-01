<?php

namespace App\Services;

use App\Models\Player;
use Illuminate\Support\Arr;

class PlayerService
{
    public function create(array $data, bool $isAdmin = false): Player
    {
        if (! $isAdmin) {
            $data['user_id'] = auth()->id();
        }

        $player = Player::create($data);

        return $player->fresh()->load('user');
    }

    public function update(Player $player, array $data, bool $isAdmin = false): Player
    {
        if (! $isAdmin) {
            $data = Arr::except($data, ['rank']);
        }

        $player->update($data);

        return $player->fresh()->load('user');
    }

    public function delete(Player $player): void
    {
        $player->delete();
    }
}
