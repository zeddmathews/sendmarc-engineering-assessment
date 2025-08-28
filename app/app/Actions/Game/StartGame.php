<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Enums\MatchStatus;

class StartGame
{
    public function handle(Game $game): void
    {
        $game->update([
            'match_status' => MatchStatus::Ongoing->value,
        ]);
    }
}
