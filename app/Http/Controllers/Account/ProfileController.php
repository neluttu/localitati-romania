<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserProfileRequest;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        // Accounts created through Google or Facebook never get a profile row,
        // so the relation can be null. An empty instance keeps the form
        // renderable without writing anything on a GET.
        $profile = $user->profile ?? new UserProfile;

        // Use dashboard view if accessing from dashboard routes
        $view = request()->routeIs('dashboard.*') ? 'dashboard.profile.edit' : 'account.profile';

        return view($view, compact('user', 'profile'));
    }

    public function update(UserProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        // Saving is the point at which a profile has to exist, so create it
        // here for the accounts that never got one at sign-up.
        $profile = $user->profile()->firstOrCreate([]);

        $data = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Upload Avatar dacă există
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('avatar')) {
            $this->updateAvatar($profile, $request->file('avatar'));
        }

        $profile->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'] ?? null,
        ]);

        return back()->with('success', 'Profil actualizat cu succes!');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $profile = $user->profile;

        if ($profile->avatar && $this->isLocalAvatar($profile->avatar)) {
            Storage::disk('public')->delete($profile->avatar);
        }

        $profile->update(['avatar' => null]);

        return back()->with('success', 'Avatarul a fost șters.');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function updateAvatar($profile, $file): void
    {
        // Șterge avatarul vechi doar dacă este stocat local
        if ($profile->avatar && $this->isLocalAvatar($profile->avatar)) {
            Storage::disk('public')->delete($profile->avatar);
        }

        // Salvează avatarul nou
        $path = $file->store('avatars', 'public');

        $profile->update(['avatar' => $path]);
    }

    private function isLocalAvatar(?string $avatarPath = null): bool
    {
        if (! $avatarPath) {
            return false;
        }

        // local = începe cu "avatars/"
        return str_starts_with($avatarPath, 'avatars/');
    }
}
