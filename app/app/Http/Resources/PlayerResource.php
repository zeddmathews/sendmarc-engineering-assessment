<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;

class PlayerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'rank' => $this->rank,
            'country' => $this->country,
            'points' => $this->points,
            'games_won' => $this->games_won,
            'can_edit' => Auth::user()?->can('update', $this->resource) ?? false,
            'can_delete' => Auth::user()?->can('delete', $this->resource) ?? false,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
        ];
    }
}
