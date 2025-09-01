<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\MatchStatus;

class Game extends Model
{
    protected $fillable = [
        'played_at',
        'winner_id',
        'match_status',
        'player1_id',
        'player2_id',
        'player1_points',
        'player2_points',
    ];

    protected $casts = [
        'player1_points' => 'integer',
        'player2_points' => 'integer',
        'played_at' => 'datetime',
        'match_status' => MatchStatus::class,
    ];


    public function winner(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    public function player1(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player1_id')->withDefault([
            'first_name' => 'Unknown',
            'last_name' => 'Player',
        ]);
    }

    public function player2(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player2_id')->withDefault([
            'first_name' => 'Unknown',
            'last_name' => 'Player',
        ]);
    }
}
