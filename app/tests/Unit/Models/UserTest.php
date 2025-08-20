<?php

use App\Models\User;
use App\Models\Player

it('can determine if a user is admin', function () {
    $admin = User::factory()->make(['is_admin' => true]);
    $user = User::factory()->make(['is_admin' => false]);

    expect($admin->isAdmin())->toBeTrue();
    expect($user->isAdmin())->toBeFalse();
});

it('can have a related player', function () {
    $user = User::factory()->create();
    $player = $user->player()->create([
        'first_name' => 'Test',
        'last_name' => 'Player',
        'email' => 'testplayer@example.com',
        'points' => 100
    ]);

    expect($user->player)->toBeInstanceOf(Player::class);
    expect($user->player->id)->toBe($player->id);
});
