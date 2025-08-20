<?php

use App\Models\Game;
use App\Models\Player;
use Illuminate\Support\Carbon;

it('can assign player1 and player2', function () {
    $players = Player::factory()->count(2)->create();
    $game = Game::factory()->create([
        'player1_id' => $players[0]->id,
        'player2_id' => $players[1]->id,
    ]);

    expect($game->player1->id)->toBe($players[0]->id);
    expect($game->player2->id)->toBe($players[1]->id);
});

it('casts points to integer and played_at to datetime', function () {
    $game = Game::factory()->make([
        'player1_points' => '30',
        'player2_points' => '15',
        'played_at' => now(),
    ]);

    expect($game->player1_points)->toBeInt()->toBe(30);
    expect($game->player2_points)->toBeInt()->toBe(15);
    expect($game->played_at)->toBeInstanceOf(Carbon::class);
});
