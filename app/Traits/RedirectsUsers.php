<?php

namespace App\Traits;

use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;

trait RedirectsUsers
{
    /**
     * Redirect centralizat pentru TOATE cazurile:
     * - login
     * - register
     * - email verification
     * - password reset
     * - social login
     */
    protected function redirectUser($user): RedirectResponse
    {
        // Email neconfirmat
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Admin & Manager → Dashboard Admin
        if (in_array($user->role, [UserRole::Admin])) {
            return redirect()->route('admin.dashboard');
        }

        // Orice alt rol → Dashboard User
        return redirect()->route('account.index');
    }
}
