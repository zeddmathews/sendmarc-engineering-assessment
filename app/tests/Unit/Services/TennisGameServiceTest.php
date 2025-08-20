<?php

use App\Models\Game;
use App\Models\Player;
use App\Services\TennisGameService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create two players
    $this->player1 = Player::factory()->create();
    $this->player2 = Player::factory()->create();

    // Create a game in ongoing state
    $this->game = Game::create([
        'player1_id' => $this->player1->id,
        'player2_id' => $this->player2->id,
        'player1_points' => 0,
        'player2_points' => 0,
        'match_status' => 'ongoing',
        'played_at' => now(),
    ]);

    $this->service = new TennisGameService($this->game);
});

it('serializes game correctly', function () {
    $data = $this->service->serialize();

    expect($data)->toHaveKeys([
        'id', 'player1', 'player2', 'score', 'game_over', 'winner', 'played_at', 'match_status'
    ]);

    expect($data['score'])->toBeArray();
    expect($data['game_over'])->toBeFalse();
    expect($data['winner'])->toBeNull();
});

it('increments points correctly below 40', function () {
    $this->service->pointFor($this->player1);
    $this->game->refresh();

    expect($this->game->player1_points)->toBe(1);
    expect($this->game->player2_points)->toBe(0);
});

it('handles deuce and advantage correctly', function () {
    $this->game->player1_points = 3; // 40
    $this->game->player2_points = 3; // 40
    $this->game->save();

    $this->service->pointFor($this->player1);
    $this->game->refresh();

    expect($this->game->player1_points)->toBe(4); // advantage
    expect($this->game->player2_points)->toBe(3);

    // Player2 scores to revert to deuce
    $this->service->pointFor($this->player2);
    $this->game->refresh();

    expect($this->game->player1_points)->toBe(3);
    expect($this->game->player2_points)->toBe(3);
});

it('completes game and sets winner when a player wins', function () {
    $this->game->player1_points = 3;
    $this->game->player2_points = 2;
    $this->game->save();

    $this->service->pointFor($this->player1);
    $this->game->refresh();

    expect($this->game->match_status)->toBe('completed');
    expect($this->game->winner_id)->toBe($this->player1->id);
    expect($this->service->isGameOver())->toBeTrue();
    expect($this->service->winner()->id)->toBe($this->player1->id);
});

it('does nothing if game is not ongoing', function () {
    $this->game->match_status = 'completed';
    $this->game->save();

    $this->service->pointFor($this->player2);
    $this->game->refresh();

    expect($this->game->player2_points)->toBe(0);
});
it('does nothing if player not part of the game scores', function () {
    $otherPlayer = Player::factory()->create();

    $this->service->pointFor($otherPlayer);
    $this->game->refresh();

    expect($this->game->player1_points)->toBe(0);
    expect($this->game->player2_points)->toBe(0);
});

it('does not increment points if game is upcoming', function () {
    $this->game->match_status = 'upcoming';
    $this->game->save();

    $this->service->pointFor($this->player1);
    $this->game->refresh();

    expect($this->game->player1_points)->toBe(0);
    expect($this->game->player2_points)->toBe(0);
});

it('handles completed game already having a winner', function () {
    $this->game->match_status = 'completed';
    $this->game->winner_id = $this->player2->id;
    $this->game->save();

    $this->service->pointFor($this->player1);
    $this->game->refresh();

    expect($this->game->winner_id)->toBe($this->player2->id);
    expect($this->game->player1_points)->toBe(0);
    expect($this->game->player2_points)->toBe(0);
});
