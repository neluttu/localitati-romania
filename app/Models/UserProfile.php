<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'avatar',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        // Dacă este URL complet (Google/Facebook etc.)
        if ($this->avatar && str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        // Dacă este imagine locală salvată în storage
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // fallback, imagine default
        return asset('images/default-avatar.jpg');
    }


}
