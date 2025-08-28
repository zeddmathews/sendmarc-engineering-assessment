<?php

namespace App\Http\Requests\Game;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Game;

class GameUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('game'));
    }

    public function rules(): array
    {
        return [
            'played_at' => 'required|date',
            'winner_id' => 'nullable|exists:players,id',
            'match_status' => 'required|string|in:upcoming,ongoing,completed,cancelled,tied',
            'player1_id' => 'nullable|exists:players,id',
            'player2_id' => 'nullable|exists:players,id',
            'player1_points' => 'nullable|integer|min:0',
            'player2_points' => 'nullable|integer|min:0',

        ];
    }
}
