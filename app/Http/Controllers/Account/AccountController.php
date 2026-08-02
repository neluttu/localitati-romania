<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\DeleteAccountRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('account.index');
    }

    public function confirmDelete(): View
    {
        return view('dashboard.account.delete');
    }

    /**
     * Soft-delete the account and everything that speaks for it. The sites are
     * deleted explicitly rather than left to a cascade, because the API tokens
     * have to stop answering the moment this returns - not whenever the purge
     * gets around to them thirty days later.
     */
    public function destroy(DeleteAccountRequest $request): RedirectResponse
    {
        $user = $request->user();

        DB::transaction(function () use ($user): void {
            $user->sites()->delete();
            $user->delete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with(
            'status',
            'Contul tău a fost șters. Datele se șterg definitiv după 30 de zile; până atunci ne poți scrie ca să îl recuperăm.'
        );
    }
}
