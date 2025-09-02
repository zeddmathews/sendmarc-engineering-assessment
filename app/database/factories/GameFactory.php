<?php

namespace Database\Factories;

use App\Enums\MatchStatus;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'played_at' => now(),
            'match_status' => MatchStatus::Upcoming->value,
            'player1_points' => 0,
            'player2_points' => 0,
            'winner_id' => null,
            'player1_id' => null,
            'player2_id' => null,
        ];
    }

    public function ongoing(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'match_status' => MatchStatus::Ongoing->value,
        ]);
    }

    public function completed(): Factory
    {
        return $this->state(function (array $attributes) {
            $player1 = Player::factory()->create();
            $player2 = Player::factory()->create();

            return [
                'match_status' => MatchStatus::Completed->value,
                'winner_id' => $player1->id,
                'player1_id' => $player1->id,
                'player2_id' => $player2->id,
                'player1_points' => 4,
                'player2_points' => 0,
            ];
        });
    }

    public function withPlayers(Player $player1, Player $player2): Factory
    {
        return $this->state(fn (array $attributes) => [
            'player1_id' => $player1->id,
            'player2_id' => $player2->id,
        ]);
    }
}
