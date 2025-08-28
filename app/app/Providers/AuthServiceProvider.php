<?php

namespace App\Providers;

use App\Models\Game;
use App\Policies\GamePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Game::class => GamePolicy::class,
    ];
}
