<?php

use App\Models\User;
use App\Models\Game;
use App\Models\Player;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\actingAs;

test('unauthenticated users cannot access protected routes', function () {
    $player = Player::factory()->create();

    // Attempt to access a protected route without being authenticated.
    getJson(route('api.players.show', $player->id))
        ->assertStatus(401);
});

test('unauthenticated users cannot access admin-only routes', function () {
    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    $gameData = [
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'played_at' => now(),
        'match_status' => 'upcoming',
    ];

    postJson(route('api.games.store'), $gameData)
        ->assertStatus(401);
});

test('non-admin users cannot access admin-only routes', function () {
    $user = User::factory()->create();
    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    actingAs($this->$user, 'sanctum');

    $gameData = [
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'played_at' => now(),
        'match_status' => 'upcoming',
    ];

    postJson(route('api.games.store'), $gameData)
        ->assertStatus(403);
});
