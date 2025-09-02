<?php

use App\Enums\MatchStatus;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->user = User::factory()->create(['is_admin' => true]);
});

test('an admin can create a new game via the API', function () {
    actingAs($this->user, 'sanctum');

    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    $gameData = [
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'played_at' => now()->toDateTimeString(),
        'match_status' => MatchStatus::Upcoming->value,
    ];

    $response = $this->postJson('api.games.store', $gameData);

    $response->assertStatus(201);

    assertDatabaseHas('games', [
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'match_status' => MatchStatus::Upcoming->value,
    ]);

    $game = Game::first();

    $response->assertJson(
        fn ($json) => $json->where('data.id', $game->id)
            ->where('data.player1_id', $player1->id)
            ->where('data.player2_id', $player2->id)
            ->where('data.match_status', MatchStatus::Upcoming->value)
            ->etc()
    );
});

test('an admin can start an upcoming game', function () {
    actingAs($this->user, 'sanctum');

    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    $game = Game::factory()->create([
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'match_status' => MatchStatus::Upcoming->value,
        'player1_points' => 0,
        'player2_points' => 0,
    ]);
    $response = $this->postJson(route('api.games.start', $game->id));
    $response->assertStatus(200);

    assertDatabaseHas('games', [
        'id' => $game->id,
        'match_status' => MatchStatus::Ongoing->value,
    ]);

    $response->assertJson(
        fn ($json) => $json->where('data.id', $game->id)
            ->where('data.match_status', MatchStatus::Ongoing->value)
            ->etc()
    );
});

test('points can be assigned to players in an ongoing game', function () {
    actingAs($this->user, 'sanctum');

    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    $game = Game::factory()->create([
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'match_status' => MatchStatus::Ongoing->value,
        'player1_points' => 0,
        'player2_points' => 0,
    ]);

    $response = $this->postJson(route('api.games.assignPoint', $game->id), [
        'player_id' => $player1->id,
    ]);
    $response->assertStatus(200);

    assertDatabaseHas('games', [
        'id' => $game->id,
        'player1_points' => 1,
        'player2_points' => 0,
        'match_status' => MatchStatus::Ongoing->value,
    ]);

    $response = $this->postJson(route('api.games.assignPoint', $game->id), [
        'player_id' => $player2->id,
    ]);
    $response->assertStatus(200);

    assertDatabaseHas('games', [
        'id' => $game->id,
        'player1_points' => 1,
        'player2_points' => 1,
        'match_status' => MatchStatus::Ongoing->value,
    ]);
});

test('a game is completed and a winner is declared', function () {
    actingAs($this->user, 'sanctum');

    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    $game = Game::factory()->create([
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
        'match_status' => MatchStatus::Ongoing->value,
        'player1_points' => 2,
        'player2_points' => 0,
    ]);

    $response = postJson(route('api.games.assignPoint', $game->id), [
        'player_id' => $player1->id,
    ]);

    $response->assertStatus(200);

    assertDatabaseHas('games', [
        'id' => $game->id,
        'match_status' => MatchStatus::Completed->value,
        'winner_id' => $player1->id,
        'player1_points' => 3,
        'player2_points' => 0,
    ]);
});

test('a user can fetch the details for a specific game', function () {
    $game = Game::factory()->create();

    $response = getJson(route('api.games.show', $game->id));

    $response->assertStatus(200);

    $response->assertJson(
        fn ($json) => $json->where('data.id', $game->id)
            ->where('data.player1_points', $game->player1_points)
            ->etc()
    );
});

test('the upcoming games endpoint returns the correct games', function () {
    $upcomingGame1 = Game::factory()->create(['played_at' => now()->addHours(1), 'match_status' => MatchStatus::Upcoming->value]);
    $upcomingGame2 = Game::factory()->create(['played_at' => now()->addHours(2), 'match_status' => MatchStatus::Upcoming->value]);

    $completedGame = Game::factory()->completed()->create();

    $response = getJson(route('api.games.upcoming'));

    $response->assertStatus(200);

    $response->assertJsonCount(2, 'data');

    $response->assertJson([
        'data' => [
            [
                'id' => $upcomingGame1->id,
                'match_status' => MatchStatus::Upcoming->value,
            ],
            [
                'id' => $upcomingGame2->id,
                'match_status' => MatchStatus::Upcoming->value,
            ],
        ],
    ]);
});
