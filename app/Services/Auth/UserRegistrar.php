<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Events\UserRegistered;
use App\Enums\UserRole;

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
