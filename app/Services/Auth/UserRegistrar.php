<?php

namespace App\Services\Auth;

use App\Enums\UserRole;
use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRegistrar
{
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'provider' => 'local',
                'provider_id' => null,
                'role' => UserRole::User,
                // Consent has to be demonstrable later, so record when it was
                // given rather than only that the form was submitted.
                'terms_accepted_at' => now(),
            ]);

            $user->profile()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
            ]);

            event(new UserRegistered($user));

            return $user;
        });
    }
}
