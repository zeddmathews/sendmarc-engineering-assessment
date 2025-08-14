<?php

namespace App\Jobs;

use App\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateGamesWon implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function handle(): void
    {
        $this->player->increment('games_won');
    }
}
