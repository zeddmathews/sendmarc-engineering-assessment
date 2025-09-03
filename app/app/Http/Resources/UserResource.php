<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'can_edit' => Auth::user()?->can('update', $this->resource) ?? false,
            'can_delete' => Auth::user()?->can('delete', $this->resource) ?? false,
        ];
    }
}
