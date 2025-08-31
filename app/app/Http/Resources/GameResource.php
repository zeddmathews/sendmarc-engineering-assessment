<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PlayerResource;

class GameResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'played_at' => $this->played_at,
            'match_status' => $this->match_status,
            'player1_points' => $this->player1_points,
            'player2_points' => $this->player2_points,
            'player1' => $this->whenLoaded('player1', fn () => new PlayerResource($this->player1)),
            'player2' => $this->whenLoaded('player2', fn () => new PlayerResource($this->player2)),
            'winner' => $this->whenLoaded('winner', fn () => new PlayerResource($this->winner)),
            'can_edit' => Auth::user()?->can('update', $this->resource) ?? false,
            'can_delete' => Auth::user()?->can('delete', $this->resource) ?? false,
            'can_award_points' => Auth::user()?->can('awardPoints', $this->resource) ?? false,
        ];
    }
}
