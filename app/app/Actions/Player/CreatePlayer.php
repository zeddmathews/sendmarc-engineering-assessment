<?php

namespace App\Actions\Player;

use App\Models\Player;

class CreatePlayer
{
    public function handle(array $data): Player
    {
        return Player::create($data);
    }
}
