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
    expect($this->service->isGameOver())->toBeFalse();
});

test('assignPoint correctly sets a winner with two-point lead', function () {
    $player1 = $this->game->player1;
    $player2 = $this->game->player2;

    $this->game->update(['player1_points' => 3, 'player2_points' => 3]);

    $this->service->assignPoint($player1);
    expect($this->service->getDisplayScore($this->game->player1_points, $this->game->player2_points))->toBe('Advantage');
    expect($this->service->isGameOver())->toBeFalse();

    $this->service->assignPoint($player1);
    expect($this->service->isGameOver())->toBeTrue();
    expect($this->game->winner_id)->toBe($player1->id);
});

test('getDisplayScore returns "Love All" when both players have 0 points', function () {
    $score = $this->service->getDisplayScore(0, 0);
    expect($score)->toBe('Love All');
});

test('getDisplayScore returns normal scores for 1-3 points', function () {
    expect($this->service->getDisplayScore(1, 0))->toBe('Fifteen');
    expect($this->service->getDisplayScore(2, 1))->toBe('Thirty');
    expect($this->service->getDisplayScore(3, 2))->toBe('Forty');
});

test('getDisplayScore handles Deuce correctly', function () {
    expect($this->service->getDisplayScore(3, 3))->toBe('Deuce');
    expect($this->service->getDisplayScore(4, 4))->toBe('Deuce');
    expect($this->service->getDisplayScore(5, 5))->toBe('Deuce');
});

test('getDisplayScore handles Advantage correctly', function () {
    expect($this->service->getDisplayScore(4, 3))->toBe('Advantage');
    expect($this->service->getDisplayScore(5, 4))->toBe('Advantage');
    expect($this->service->getDisplayScore(6, 5))->toBe('Advantage');
});

test('getDisplayScore handles back-and-forth Deuce-Advantage', function () {
    expect($this->service->getDisplayScore(3, 3))->toBe('Deuce');
    expect($this->service->getDisplayScore(4, 3))->toBe('Advantage');
    expect($this->service->getDisplayScore(4, 4))->toBe('Deuce');
    expect($this->service->getDisplayScore(5, 4))->toBe('Advantage');
});
