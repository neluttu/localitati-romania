<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Enums\UserRole;
use App\Events\UserLoggedIn;
use App\Events\UserRegistered;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAuthService
{
    public function handle(string $provider, ProviderUser $providerUser): User
    {
        // 1. Caut utilizator pe baza provider + provider_id
        $user = User::where('provider', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        if ($user) {
            $this->sync($user, $provider, $providerUser);
            event(new UserLoggedIn($user));
            return $user;
        }

        // 2. Caut pe baza emailului (cont creat anterior)
        $user = User::where('email', $providerUser->getEmail())->first();

        if ($user) {
            $user->forceFill([
                'provider' => $provider,
                'provider_id' => $providerUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();

            $this->sync($user, $provider, $providerUser);
            event(new UserLoggedIn($user));
            return $user;
        }

        // 3. Creez un utilizator complet nou
        $user = User::create([
            'email' => $providerUser->getEmail(),
            'provider' => $provider,
            'provider_id' => $providerUser->getId(),
            'password' => null,
            'role' => UserRole::User,
            'email_verified_at' => now(),
        ]);

        // 4. Creez profil aferent
        $this->createProfile($user, $provider, $providerUser);
        event(new UserRegistered($user));
        event(new UserLoggedIn($user));

        return $user;
    }



    // =====================================================
    // Sincronizare profil
    // =====================================================

    protected function sync(User $user, string $provider, ProviderUser $providerUser): void
    {
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        $names = $this->extractNames($provider, $providerUser);
        $avatar = $providerUser->getAvatar();

        $profile->update(
            $this->mapProfileFields($names, $avatar, $profile)
        );
    }



    // =====================================================
    // Creare profil nou
    // =====================================================

    protected function createProfile(User $user, string $provider, ProviderUser $providerUser): void
    {
        $names = $this->extractNames($provider, $providerUser);
        $avatar = $providerUser->getAvatar();

        $user->profile()->create(
            $this->mapProfileFields($names, $avatar)
        );
    }



    // =====================================================
    // MAPEAZĂ datele de profil (firstname, lastname, avatar)
    // =====================================================

    protected function mapProfileFields(array $names, ?string $avatar, $currentProfile = null): array
    {
        $data = [];

        if ($names['first_name'] ?? null) {
            $data['first_name'] = $names['first_name'];
        }

        if ($names['last_name'] ?? null) {
            $data['last_name'] = $names['last_name'];
        }

        // avatar update doar dacă se schimbă (mai safe)
        if ($avatar && (!$currentProfile || $currentProfile->avatar !== $avatar)) {
            $data['avatar'] = $avatar;
        }

        return $data;
    }



    // =====================================================
    // Extract first_name & last_name (Google perfect / Facebook smart)
    // =====================================================

    protected function extractNames(string $provider, ProviderUser $providerUser): array
    {
        // GOOGLE (trimite firstname + lastname corect)
        if ($provider === 'google') {
            return [
                'first_name' => $providerUser->user['given_name'] ?? null,
                'last_name' => $providerUser->user['family_name'] ?? null,
            ];
        }

        // FACEBOOK (nu trimite firstname/lastname → split inteligent)
        if ($provider === 'facebook') {

            $fullName = trim($providerUser->getName());
            $parts = preg_split('/\s+/', $fullName);

            $first = array_shift($parts);        // primul cuvânt
            $last = implode(' ', $parts);        // restul numelui (OK și pt nume compuse)

            return [
                'first_name' => $first,
                'last_name' => $last,
            ];
        }

        // fallback pentru alți provideri
        return [
            'first_name' => null,
            'last_name' => null,
        ];
    }
}
