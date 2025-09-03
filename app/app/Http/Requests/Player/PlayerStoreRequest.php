<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Player;
use App\Enums\PlayerRank;
use Illuminate\Validation\Rule;

class PlayerStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Player::class);
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:players,email',
            'rank' => ['nullable', Rule::in(array_map(fn($r) => $r->value, PlayerRank::cases()))],
            'country' => 'nullable|string|max:255',
            'points' => 'nullable|integer|min:0',
            'user_id'   => 'required|exists:users,id',
        ];
    }
}
