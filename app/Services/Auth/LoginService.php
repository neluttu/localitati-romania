<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginService
{
    public function login(array $data): void
    {
        $this->ensureIsNotRateLimited($data['email']);

        $remember = ! empty($data['remember']);

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $remember)) {
            RateLimiter::hit($this->throttleKey($data['email']));
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        RateLimiter::clear($this->throttleKey($data['email']));
        session()->regenerate();
        Auth::user()->markLogin();
    }

    private function ensureIsNotRateLimited(string $email): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey($email), 5)) {
            return;
        }

        throw ValidationException::withMessages([
            'email' => 'Too many attempts. Try again in '.RateLimiter::availableIn($this->throttleKey($email)).' seconds.',
        ]);
    }

    private function throttleKey(string $email): string
    {
        return 'login:'.strtolower($email);
    }
}
