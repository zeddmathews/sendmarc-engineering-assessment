<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Player;

class PlayersTableSeeder extends Seeder
{
    public function run(): void
    {
        $players = [
            ['first_name' => 'Roger', 'last_name' => 'Federer', 'country' => 'Switzerland', 'ranking' => '3'],
            ['first_name' => 'Rafael', 'last_name' => 'Nadal', 'country' => 'Spain', 'ranking' => '2'],
            ['first_name' => 'Novak', 'last_name' => 'Djokovic', 'country' => 'Serbia', 'ranking' => '1'],
            ['first_name' => 'Serena', 'last_name' => 'Williams', 'country' => 'USA', 'ranking' => '5'],
            ['first_name' => 'Venus', 'last_name' => 'Williams', 'country' => 'USA', 'ranking' => '7'],
            ['first_name' => 'Maria', 'last_name' => 'Sharapova', 'country' => 'Russia', 'ranking' => '6'],
            ['first_name' => 'Andy', 'last_name' => 'Murray', 'country' => 'United Kingdom', 'ranking' => '4'],
            ['first_name' => 'Pete', 'last_name' => 'Sampras', 'country' => 'USA', 'ranking' => '8'],
            ['first_name' => 'Andre', 'last_name' => 'Agassi', 'country' => 'USA', 'ranking' => '9'],
            ['first_name' => 'Steffi', 'last_name' => 'Graf', 'country' => 'Germany', 'ranking' => '10'],
        ];


        foreach ($players as $player) {
            Player::firstOrCreate([
                'first_name' => $player['first_name'],
                'last_name' => $player['last_name'],
            ], [
                'country' => $player['country'],
                'ranking' => $player['ranking'],
            ]);
        }
    }
}
