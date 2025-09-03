<?php

namespace App\Jobs;

use App\Enums\MatchStatus;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateGameWon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Game $game;
    protected Player $winner;

    public function __construct(Game $game, Player $winner)
    {
        $this->game = $game;
        $this->winner = $winner;
    }

    public function handle(): void
    {
        $loser = ($this->game->player1_id === $this->winner->id)
            ? $this->game->player2
            : $this->game->player1;

        $this->game->update([
            'winner_id' => $this->winner->id,
            'match_status' => MatchStatus::Completed->value,
        ]);

        $this->winner->increment('points', $this->game->player1_points);
        $this->winner->increment('games_won');

        $loser->increment('points', $this->game->player2_points);
    }
}
