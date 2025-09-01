<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\PlayerRank;

class Player extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'rank',
        'country',
        'points'
    ];

    protected $casts = [
        'rank' => PlayerRank::class,
        'points' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVisibleTo($query, User $user)
    {
        return $user->is_admin
            ? $query
            : $query->where('user_id', $user->id);
    }
}
