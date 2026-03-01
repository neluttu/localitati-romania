<?php
declare(strict_types=1);
namespace App\Http\Controllers\Auth;

use App\Traits\RedirectsUsers;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class EmailVerificationController extends Controller
{
    use RedirectsUsers;

    public function notice(): RedirectResponse|View
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return $this->redirectUser($user)
                ->with('info', 'Emailul este deja verificat.');
        }

        return view('auth.verify-email');
    }


    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectUser($request->user())
                ->with('success', 'Emailul a fost deja verificat.');
        }

        $request->fulfill();

        return $this->redirectUser($request->user())
            ->with('success', 'Emailul a fost verificat!');
    }



    public function send(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return $this->redirectUser($request->user());
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Emailul pentru verificare email a fost trimis!');
    }

    // private function redirectUser($user): RedirectResponse
    // {
    //     if ($user->isAdmin()) {
    //         return redirect()->route('admin.dashboard');
    //     }

    //     return redirect()->route('user.dashboard');
    // }
}
