<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\PlayerRank;

class Player extends Model
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     *
     *
     *@var list<string>
     *
     * */
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

    public function stats(): HasMany
    {
        return $this->hasMany(PlayerStats::class);
    }
}
