<?php

use App\Models\Game;
use App\Models\Player;
use App\Services\GameService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $player1 = Player::factory()->create();
    $player2 = Player::factory()->create();

    $this->game = Game::factory()->create([
        'player1_id' => $player1->id,
        'player2_id' => $player2->id,
    ]);
    $this->service = new GameService($this->game);
});

test('assignPoint correctly increments player points', function () {
    $player1 = $this->game->player1()->first();

    $this->service->assignPoint($player1);

    expect($this->game->player1_points)->toBe(1);
    expect($this->game->player2_points)->toBe(0);
});

test('assignPoint correctly sets a winner when a player reaches 4 points with a two-point lead', function () {
    $player1 = $this->game->player1()->first();
    $player2 = $this->game->player2()->first();

    $this->game->update(['player1_points' => 3, 'player2_points' => 3]);

    $this->service->assignPoint($player1);
    expect($this->game->player1_points)->toBe(4);
    expect($this->game->player2_points)->toBe(3);
    expect($this->game->winner_id)->toBeNull();

    $this->service->assignPoint($player1);
    expect($this->game->player1_points)->toBe(5);
    expect($this->game->player2_points)->toBe(3);
    expect($this->game->winner_id)->toBe($player1->id);
});

test('getDisplayScore returns "Love All" when both players have 0 points', function () {
    $score = $this->service->getDisplayScore(0, 0);
    expect($score)->toBe('Love All');
});

test('getDisplayScore returns the correct score for normal points', function () {
    $score = $this->service->getDisplayScore(1, 0);
    expect($score)->toBe('Fifteen');

    $score = $this->service->getDisplayScore(2, 1);
    expect($score)->toBe('Thirty');

    $score = $this->service->getDisplayScore(3, 2);
    expect($score)->toBe('Forty');
});

test('getDisplayScore returns "Advantage" when a player has a one-point lead after deuce', function () {
    expect($this->service->getDisplayScore(4, 3))->toBe('Advantage');
    expect($this->service->getDisplayScore(5, 4))->toBe('Advantage');
});

test('getDisplayScore returns "Deuce" when both players have 3 or more points and are tied', function () {
    expect($this->service->getDisplayScore(3, 3))->toBe('Deuce');
    expect($this->service->getDisplayScore(4, 4))->toBe('Deuce');
});
