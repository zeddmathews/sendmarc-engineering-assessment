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
    protected Player $loser;

    public function __construct(Game $game, Player $winner, Player $loser)
    {
        $this->game = $game;
        $this->winner = $winner;
        $this->loser = $loser;
    }

    public function handle(): void
    {
        $this->game->update([
            'winner_id' => $this->winner->id,
            'match_status' => MatchStatus::Completed->value,
        ]);

        $winnerPoints = $this->winner->id === $this->game->player1_id
            ? $this->game->player1_points
            : $this->game->player2_points;
        $loserPoints = $this->loser->id === $this->game->player1_id
            ? $this->game->player2_points
            : $this->game->player1_points;
        $this->winner->increment('points', $winnerPoints);
        $this->winner->increment('games_won');

        $this->loser->increment('points', $loserPoints);
    }
}
