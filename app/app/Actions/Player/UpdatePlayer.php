<?php

namespace App\Actions\Player;

use App\Models\Player;

class UpdatePlayer
{
    public function handle(Player $player, array $data): Player
    {
        $player->update($data);
        return $player->fresh();
    }
}
