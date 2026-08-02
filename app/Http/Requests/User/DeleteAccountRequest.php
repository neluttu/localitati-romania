<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DeleteAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Deleting an account is irreversible for the person doing it, so ask for
     * the password: a logged-in session left open on a shared machine should
     * not be enough to wipe someone's data.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'password' => ['required', 'string', 'current_password'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Introdu parola pentru a confirma ștergerea.',
            'password.current_password' => 'Parola introdusă nu este corectă.',
        ];
    }
}
