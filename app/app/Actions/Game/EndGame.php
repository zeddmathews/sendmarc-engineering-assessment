<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Jobs\UpdateGameWon;
use App\Enums\MatchStatus;

class EndGame
{
    public function handle(Game $game): void
    {
        if ($game->match_status !== MatchStatus::Ongoing->value) {
            return;
        }
        if ($game->winner_id) {
            $game->update(['match_status' => MatchStatus::Completed->value]);
            UpdateGameWon::dispatch($game->winner()->first());
            return;
        }

        $game->update(['match_status' => MatchStatus::Tied->value]);
    }
}
