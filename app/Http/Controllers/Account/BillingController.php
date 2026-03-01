<?php

namespace App\Http\Controllers\Account;

use Illuminate\View\View;
use App\Models\UserBillingProfile;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\User\UserBillingProfileRequest;

class BillingController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        // ia profilul implicit sau primul găsit
        $billing = $user->billingProfiles()->default()->first()
            ?? $user->billingProfiles()->first()
            ?? new UserBillingProfile();

        return view('account.billing', [
            'user' => $user,
            'billing' => $billing
        ]);
    }

    public function update(UserBillingProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();
        $data['is_company'] = $request->boolean('is_company');

        // verifică dacă userul are deja profil de facturare
        $billing = $user->billingProfiles()->first();

        /*
        |--------------------------------------------------------------------------
        | Dacă nu are, îl creăm și îl setăm default
        |--------------------------------------------------------------------------
        */
        if (!$billing) {
            $data['is_default'] = true;

            $user->billingProfiles()->create($data);

            return back()->with('success', 'Datele de facturare au fost salvate!');
        }

        /*
        |--------------------------------------------------------------------------
        | Dacă are, doar actualizăm profilul existent
        |--------------------------------------------------------------------------
        */
        $billing->update($data);

        if (!$billing->is_company) {
            $billing->clearCompanyFields();
        }


        return back()->with('success', 'Datele de facturare au fost actualizate!');
    }
}
