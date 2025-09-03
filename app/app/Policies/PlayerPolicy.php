<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\User;

class PlayerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Player $player): bool
    {
        return $user->is_admin || $player->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->is_admin || ! $user->player->exists();
    }

    public function update(User $user, Player $player): bool
    {
        return $user->is_admin || $player->user_id === $user->id;
    }

    public function delete(User $user, Player $player): bool
    {
        return $user->is_admin || $player->user_id === $user->id;
    }

    public function restore(User $user): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, Player $player): bool
    {
        return $user->is_admin;
    }
}
