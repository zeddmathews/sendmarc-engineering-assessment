<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Enums\PlayerRank;
use App\Models\User;

class PlayersTableSeeder extends Seeder
{
    public function run(): void
    {
        $players = [
            ['first_name' => 'Roger', 'last_name' => 'Federer', 'email' => 'roger@example.com', 'country' => 'Switzerland', 'games_won' => 5,'rank' => 'Platinum', 'points' => 1000],
            ['first_name' => 'Rafael', 'last_name' => 'Nadal', 'email' => 'rafael@example.com', 'country' => 'Spain', 'games_won' => 8,'rank' => 'Gold', 'points' => 900],
            ['first_name' => 'Novak', 'last_name' => 'Djokovic', 'email' => 'novak@example.com', 'country' => 'Serbia', 'games_won' => 7,'rank' => 'Gold', 'points' => 950],
            ['first_name' => 'Serena', 'last_name' => 'Williams', 'email' => 'serena@example.com', 'country' => 'USA', 'games_won' => 2,'rank' => 'Silver', 'points' => 750],
            ['first_name' => 'Venus', 'last_name' => 'Williams', 'email' => 'venus@example.com', 'country' => 'USA', 'games_won' => 6,'rank' => 'Silver', 'points' => 700],
            ['first_name' => 'Maria', 'last_name' => 'Sharapova', 'email' => 'maria@example.com', 'country' => 'Russia', 'games_won' => 9,'rank' => 'Silver', 'points' => 680],
            ['first_name' => 'Andy', 'last_name' => 'Murray', 'email' => 'andy@example.com', 'country' => 'United Kingdom', 'games_won' => 10,'rank' => 'Gold', 'points' => 800],
            ['first_name' => 'Pete', 'last_name' => 'Sampras', 'email' => 'pete@example.com', 'country' => 'USA', 'games_won' => 1,'rank' => 'Silver', 'points' => 650],
            ['first_name' => 'Andre', 'last_name' => 'Agassi', 'email' => 'andre@example.com', 'country' => 'USA', 'games_won' => 4,'rank' => 'Silver', 'points' => 640],
            ['first_name' => 'Steffi', 'last_name' => 'Graf', 'email' => 'steffi@example.com', 'country' => 'Germany', 'games_won' => 3,'rank' => 'Silver', 'points' => 700],
        ];

        foreach ($players as $p) {
            $user = User::factory()->create([
                'name' => "{$p['first_name']}  {$p['last_name']}",
                'email' => $p['email'],
            ]);
            $player = Player::create([
                    'first_name' => $p['first_name'],
                    'last_name' => $p['last_name'],
                    'country' => $p['country'],
                    'email' => $p['email'],
                    'games_won' => $p['games_won'],
                    'rank' => PlayerRank::from($p['rank']),
                    'points' => $p['points'] ?? 0,
                    'user_id' => $user->id,
            ]);
        }
    }
}
