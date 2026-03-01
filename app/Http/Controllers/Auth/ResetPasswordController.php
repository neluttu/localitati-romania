<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\ResetPasswordService;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function show($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request()->email,
        ]);
    }

    public function reset(ResetPasswordRequest $request, ResetPasswordService $service)
    {
        $status = $service->reset($request->validated());

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
