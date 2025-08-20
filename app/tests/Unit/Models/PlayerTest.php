<?php

use App\Models\Player;
use App\Models\User;

it('can belong to a user', function () {
    $user = User::factory()->create();
    $player = Player::factory()->create(['user_id' => $user->id]);

    expect($player->user->id)->toBe($user->id);
});

it('casts points to integer', function () {
    $player = Player::factory()->make(['points' => '250']);
    expect($player->points)->toBeInt()->toBe(250);
});
