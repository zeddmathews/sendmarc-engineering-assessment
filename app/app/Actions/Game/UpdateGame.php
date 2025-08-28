<?php

namespace App\Actions\Game;

use App\Models\Game;

class UpdateGame
{
    public function handle(Game $game, array $data): Game
    {
        $game->update($data);

        return $game;
    }
}
