<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PlayerRank;
use Illuminate\Validation\Rule;

class PlayerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $player = $this->route('player');
        return $this->user()->can('update', $player);
    }

    public function rules(): array
    {
        $player = $this->route('player');
        $rules = [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name'  => 'sometimes|required|string|max:255',
            'email'      => ['sometimes', 'required', 'email', Rule::unique('players')->ignore($player->id)],
            'country'    => 'nullable|string|max:255',
            'points'     => 'nullable|integer|min:0',
        ];
        if ($this->user()->is_admin) {
            $rules['rank'] = ['nullable', Rule::in(array_map(fn($r) => $r->value, PlayerRank::cases()))];
        }
        return $rules;
    }
}
