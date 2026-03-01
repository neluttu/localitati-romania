<?php
declare(strict_types=1);
namespace App\Models;

use App\Enums\UserRole;
use App\Models\UserBillingProfile;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'last_login_at',
        'last_login_ip',
        'last_login_user_agent',
        'login_count',
        'avatar',
        'provider_id',
        'provider',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'role' => UserRole::class
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    public function isUser(): bool
    {
        return $this->role === UserRole::User;
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function fullName(): string
    {
        if (!$this->profile) {
            return '';
        }

        return trim(($this->profile->first_name ?? '') . ' ' . ($this->profile->last_name ?? ''));
    }

    public function markLogin(): void
    {
        $this->last_login_at = now();
        $this->last_login_ip = request()->ip();
        $this->last_login_user_agent = request()->userAgent();
        $this->login_count = ($this->login_count ?? 0) + 1;
        $this->save();
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordQueued($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailQueued());
    }

    public function billingProfiles(): HasMany
    {
        return $this->hasMany(UserBillingProfile::class);
    }

    public function defaultBillingProfile(): HasOne
    {
        return $this->hasOne(UserBillingProfile::class)
            ->where('is_default', true);
    }
}
