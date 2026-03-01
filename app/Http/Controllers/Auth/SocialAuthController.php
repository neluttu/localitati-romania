<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Services\Auth\SocialAuthService;
use App\Traits\RedirectsUsers;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    use RedirectsUsers;

    public function redirect(string $provider): RedirectResponse
    {

        $driver = Socialite::driver($provider);

        /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
        match ($provider) {
            'facebook' => $driver->scopes(['email']),
            'google' => $driver->with(['prompt' => 'select_account']),
            default => null
        };

        return $driver->redirect();
    }

    public function callback(string $provider, SocialAuthService $auth): RedirectResponse
    {
        $providerUser = Socialite::driver($provider)->user();

        $user = $auth->handle($provider, $providerUser);

        Auth::login($user, true);

        return $this->redirectUser($user);
    }
}
