<?php

namespace App\Actions\Game;

use App\Models\Game;

class DestroyGame
{
    public function handle(Game $game): void
    {
        $game->delete();
    }
}
