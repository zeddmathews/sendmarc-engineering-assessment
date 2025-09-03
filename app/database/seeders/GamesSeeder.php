<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Enums\MatchStatus;

class GamesSeeder extends Seeder
{
    public function run(): void
    {
        $players = Player::all();

        if ($players->count() < 2) {
            $this->command->warn('Not enough players to seed games. Please run PlayersTableSeeder first.');
            return;
        }

        $pickPlayers = function () use ($players) {
            return $players->random(2)->values();
        };

        [$p1, $p2] = $pickPlayers();
        Game::create([
            'played_at' => Carbon::now()->addDays(2),
            'player1_id' => $p1->id,
            'player2_id' => $p2->id,
            'match_status' => MatchStatus::Upcoming->value,
        ]);

        [$p1, $p2] = $pickPlayers();
        Game::create([
            'played_at' => Carbon::now()->subHours(1),
            'player1_id' => $p1->id,
            'player2_id' => $p2->id,
            'player1_points' => 30,
            'player2_points' => 15,
            'match_status' => MatchStatus::Ongoing->value,
        ]);

        [$p1, $p2] = $pickPlayers();
        $winner = rand(0, 1) ? $p1 : $p2;
        $loser = $winner->id === $p1->id ? $p2 : $p1;

        Game::create([
            'played_at' => Carbon::now()->subDays(3),
            'player1_id' => $p1->id,
            'player2_id' => $p2->id,
            'player1_points' => $winner->id === $p1->id ? 40 : 20,
            'player2_points' => $winner->id === $p2->id ? 40 : 20,
            'winner_id' => $winner->id,
            'match_status' => MatchStatus::Completed->value,
        ]);

        [$p1, $p2] = $pickPlayers();
        $winner = rand(0, 1) ? $p1 : $p2;

        Game::create([
            'played_at' => Carbon::now()->subDays(7),
            'player1_id' => $p1->id,
            'player2_id' => $p2->id,
            'player1_points' => rand(20, 40),
            'player2_points' => rand(20, 40),
            'winner_id' => $winner->id,
            'match_status' => MatchStatus::Completed->value,
        ]);
    }
}
