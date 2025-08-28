<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Game;

class PointAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('game'));
    }

    public function rules(): array
    {
        return [
            'player_id' => [
                'required',
                'exists:players,id',
                function ($attribute, $value, $fail) {
                    $game = $this->route('game');
                    if ($game && $game->player1_id !== (int) $value && $game->player2_id !== (int) $value) {
                        $fail('The selected player does not belong to this game');
                    }
                    if ($game && $game->winner_id) {
                        $fail('This game has already ended');
                    }
                }
            ],
        ];
    }
}
