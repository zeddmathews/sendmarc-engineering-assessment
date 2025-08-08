<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Enums\PlayerRank;

class PlayersTableSeeder extends Seeder
{
    public function run(): void
    {
        $players = [
            ['first_name' => 'Roger', 'last_name' => 'Federer', 'email' => 'roger@example.com', 'country' => 'Switzerland', 'rank' => 'Platinum', 'points' => 1000],
            ['first_name' => 'Rafael', 'last_name' => 'Nadal', 'email' => 'rafael@example.com', 'country' => 'Spain', 'rank' => 'Gold', 'points' => 900],
            ['first_name' => 'Novak', 'last_name' => 'Djokovic', 'email' => 'novak@example.com', 'country' => 'Serbia', 'rank' => 'Gold', 'points' => 950],
            ['first_name' => 'Serena', 'last_name' => 'Williams', 'email' => 'serena@example.com', 'country' => 'USA', 'rank' => 'Silver', 'points' => 750],
            ['first_name' => 'Venus', 'last_name' => 'Williams', 'email' => 'venus@example.com', 'country' => 'USA', 'rank' => 'Silver', 'points' => 700],
            ['first_name' => 'Maria', 'last_name' => 'Sharapova', 'email' => 'maria@example.com', 'country' => 'Russia', 'rank' => 'Silver', 'points' => 680],
            ['first_name' => 'Andy', 'last_name' => 'Murray', 'email' => 'andy@example.com', 'country' => 'United Kingdom', 'rank' => 'Gold', 'points' => 800],
            ['first_name' => 'Pete', 'last_name' => 'Sampras', 'email' => 'pete@example.com', 'country' => 'USA', 'rank' => 'Silver', 'points' => 650],
            ['first_name' => 'Andre', 'last_name' => 'Agassi', 'email' => 'andre@example.com', 'country' => 'USA', 'rank' => 'Silver', 'points' => 640],
            ['first_name' => 'Steffi', 'last_name' => 'Graf', 'email' => 'steffi@example.com', 'country' => 'Germany', 'rank' => 'Silver', 'points' => 700],
        ];


        foreach ($players as $player) {
            Player::updateOrCreate(
                ['first_name' => $player['first_name'], 'last_name' => $player['last_name']],
                [
                    'country' => $player['country'],
                    'email' => $player['email'],
                    'rank' => PlayerRank::from($player['rank']),
                    'points' => $player['points'] ?? 0,
                ]
            );
        }
    }
}
