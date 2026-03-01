<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Password;

class ForgotPasswordService
{
    public function sendResetLink(array $data): string
    {
        return Password::sendResetLink($data);
    }
}
