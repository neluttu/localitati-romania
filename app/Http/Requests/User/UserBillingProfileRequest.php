<?php
declare(strict_types=1);

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserBillingProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'is_company' => ['sometimes', 'boolean'],

            // PF
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],

            // Firmă
            'company_name' => ['nullable', 'string', 'max:255'],
            'cif' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:50'],
            'iban' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:255'],

            // Address
            'country' => ['required', 'string', 'max:255'],
            'county' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],

            'phone' => ['required', 'string', 'max:255'],

            'is_default' => ['boolean'],
        ];

        /*
        |--------------------------------------------------------------------------
        | Validări condiționale
        |--------------------------------------------------------------------------
        */

        if ($this->type === 'individual') {
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];
        }

        if ($this->type === 'company') {
            // persoană de contact obligatoriu
            $rules['first_name'] = ['required', 'string', 'max:255'];
            $rules['last_name'] = ['required', 'string', 'max:255'];

            // date firmă obligatorii
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['cif'] = ['required', 'string', 'max:50'];
            $rules['registration_number'] = ['required', 'string', 'max:50'];
        }


        return $rules;
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Te rog selectează tipul de facturare.',

            'first_name.required' => 'Prenumele este obligatoriu',
            'last_name.required' => 'Numele este obligatoriu',
            'phone.required' => 'Număr telefon obligatoriu.',

            'company_name.required' => 'Denumirea firmei este obligatorie.',
            'cif.required' => 'CIF-ul este obligatoriu.',
            'registration_number.required' => 'Numărul de la Registrul Comerțului este obligatoriu.',

            'country.required' => 'Țara este obligatorie.',
            'county.required' => 'Județul este obligatoriu.',
            'city.required' => 'Orașul este obligatoriu.',
            'street.required' => 'Adresa este obligatorie.',
            'postal_code.required' => 'Codul poștal e obligatoriu.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes([
            'company_name',
            'cif',
            'registration_number',
            'iban',
            'bank_name',
        ], 'required', function ($input): bool {
            return (bool) ($input->is_company ?? false);
        });
    }

}
