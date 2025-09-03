<?php

namespace Database\Factories;

use App\Models\Player;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\PlayerRank;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'rank' => PlayerRank::Silver->value,
            'country' => fake()->countryCode(),
            'points' => fake()->numberBetween(0, 1000),
            'games_won' => fake()->numberBetween(0, 50),
            'user_id' => null,
        ];
    }

    public function withUser(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => User::factory(),
            ];
        });
    }

    public function gold(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'rank' => PlayerRank::Gold->value,
        ]);
    }

    public function platinum(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'rank' => PlayerRank::Platinum->value,
        ]);
    }
}
