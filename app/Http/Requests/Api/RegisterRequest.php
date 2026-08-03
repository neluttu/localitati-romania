<?php

namespace App\Http\Requests\Api;

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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            // The published policies say consent is taken when an account is
            // created, so this second registration path must not skip it.
            'terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Numele este obligatoriu.',
            'email.required' => 'Adresa de email este obligatorie.',
            'email.email' => 'Adresa de email nu este validă.',
            'email.unique' => 'Această adresă de email este deja înregistrată.',
            'password.required' => 'Parola este obligatorie.',
            'password.confirmed' => 'Confirmarea parolei nu coincide.',
            'terms.accepted' => 'Trebuie să accepți termenii și condițiile și politica de confidențialitate.',
        ];
    }
}
