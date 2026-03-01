<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\UserRegistrar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\RegisterRequest;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request, UserRegistrar $registrar)
    {
        $user = $registrar->register($request->validated());

        event(new Registered($user));
        Auth::login($user);


        return redirect()->route('verification.notice');
    }
}
