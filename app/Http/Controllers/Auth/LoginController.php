<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use App\Events\UserLoggedIn;
use App\Services\Auth\LoginService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Traits\RedirectsUsers;

class LoginController extends Controller
{
    use RedirectsUsers;

    public function show(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request, LoginService $service): RedirectResponse
    {

        $service->login($request->validated());
        $user = auth()->user();
        event(new UserLoggedIn($user));

        return $this->redirectUser($user);
    }
}
