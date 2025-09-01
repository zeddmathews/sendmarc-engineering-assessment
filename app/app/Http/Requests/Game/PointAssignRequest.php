<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PointAssignRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Use your policy to determine if the user can update the game.
        // This is a robust way to handle authorization.
        return $this->user()->can('update', $this->route('game'));
    }

    public function rules(): array
    {
        $game = $this->route('game');

        return [
            'player_id' => [
                'required',
                'integer',
                'exists:players,id',
                Rule::in([$game->player1_id, $game->player2_id]),
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $game = $this->route('game');

            if ($game && $game->winner_id) {
                $validator->errors()->add('game_over', 'This game has already ended.');
            }
        });
    }
}
