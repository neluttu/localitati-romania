<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()   // litere mari + mici
                    ->numbers()     // cel puțin o cifră
                    ->symbols(),    // cel puțin un simbol
            ],
        ];
    }

}
