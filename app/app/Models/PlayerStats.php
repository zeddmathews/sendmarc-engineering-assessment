<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerStats extends Model
{
    protected $fillable = [
        'player_id',
        'game_id',
        'aces',
        'double_faults',
        'first_serve_in',
        'first_serve_out',
        'points_won',
        'break_points_won',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
