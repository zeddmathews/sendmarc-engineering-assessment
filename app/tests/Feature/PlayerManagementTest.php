<?php

use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => true]);
});

test('a player can be created with an associated user', function () {
    assertDatabaseCount('players', 0);
    assertDatabaseCount('users', 1);

    $player = Player::factory()->withUser()->create();

    assertDatabaseCount('players', 1);
    assertDatabaseCount('users', 2);

    assertDatabaseHas('players', [
        'id' => $player->id,
        'user_id' => $player->user_id,
    ]);
});

test('an admin can create a new player via the API', function () {
    actingAs($this->user, 'sanctum');
    $user = User::factory()->create();
    $playerData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => $this->user->email,
        'user_id' => $user->id,
    ];

    $response = postJson(route('api.players.store'), $playerData);

    $response->assertStatus(201);

    assertDatabaseHas('players', $playerData);

    $player = Player::where('first_name', 'John')->first();

    $response->assertJson(
        fn ($json) => $json->where('data.id', $player->id)
            ->where('data.first_name', 'John')
            ->etc()
    );
});

test('an admin can update a player via the API', function () {
    actingAs($this->user, 'sanctum');

    $player = Player::factory()->create();

    $updatedData = [
        'first_name' => 'Jane',
        'last_name' => 'Smith',
    ];

    $response = putJson(route('api.players.update', $player->id), $updatedData);

    $response->assertStatus(200);

    assertDatabaseHas('players', [
        'id' => $player->id,
        'first_name' => 'Jane',
        'last_name' => 'Smith',
    ]);

    $response->assertJson(
        fn ($json) => $json->where('data.id', $player->id)
            ->where('data.first_name', 'Jane')
            ->etc()
    );
});

test('an admin can delete a player via the API', function () {
    actingAs($this->user, 'sanctum');

    $player = Player::factory()->create();

    assertDatabaseCount('players', 1);

    $response = deleteJson(route('api.players.destroy', $player->id));

    $response->assertStatus(204);

    assertDatabaseMissing('players', ['id' => $player->id]);
    assertDatabaseCount('players', 0);
});

test('an admin user can fetch a specific player via the API', function () {
    actingAs($this->user, 'sanctum');
    $player = Player::factory()->create();

    $response = getJson(route('api.players.show', $player->id));

    $response->assertStatus(200);
    $response->assertJson(
        fn ($json) => $json->where('data.id', $player->id)
            ->where('data.first_name', $player->first_name)
            ->etc()
    );
});

test('an admin user can fetch all players via the API', function () {
    actingAs($this->user, 'sanctum');
    $players = Player::factory()->count(3)->create();

    $response = getJson(route('api.players.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});
