<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlayerExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && !$user->isAdmin()) {
            $user->load('player');
        }
        if ($user && !$user->isAdmin() && !$user->player) {
            if (!$request->routeIs('players.create', 'players.store', 'logout')) {
                return redirect()->route('players.create');
            }
        }

        return $next($request);
    }
}
