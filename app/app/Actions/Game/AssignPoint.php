<?php

namespace App\Actions\Game;

use App\Models\Game;
use App\Models\Player;
use App\Enums\MatchStatus;

class AssignPoint
{
    public function handle(Game $game, Player $player): void
    {
        if ($game->winner_id) {
            return;
        }

        if ($game->player1_id === $player->id) {
            $game->player1_points++;
        } elseif ($game->player2_id === $player->id) {
            $game->player2_points++;
        }

        $diff = $game->player1_points - $game->player2_points;

        if ($game->player1_points >= 4 && $diff >= 2) {
            $game->winner_id = $game->player1_id;
            $game->match_status = MatchStatus::Completed->value;
        } elseif ($game->player2_points >= 4 && $diff <= -2) {
            $game->winner_id = $game->player2_id;
            $game->match_status = MatchStatus::Completed->value;
        }

        $game->save();
    }
}
