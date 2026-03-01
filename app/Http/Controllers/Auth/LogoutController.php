<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\LogoutService;
use App\Events\UserLoggedOut;

class LogoutController extends Controller
{
    public function __invoke(LogoutService $service)
    {
        $user = auth()->user();

        $service->logout();

        event(new UserLoggedOut($user));

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
