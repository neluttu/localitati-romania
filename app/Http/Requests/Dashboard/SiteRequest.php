<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // Allow: example.com, sub.example.com, *.example.com
            'domain' => ['required', 'string', 'max:255', 'regex:/^(\*\.)?[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?)*$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Numele site-ului este obligatoriu.',
            'name.max' => 'Numele site-ului nu poate depăși 255 de caractere.',
            'domain.required' => 'Domeniul este obligatoriu.',
            'domain.max' => 'Domeniul nu poate depăși 255 de caractere.',
            'domain.regex' => 'Domeniul nu este valid (ex: example.com, *.example.com).',
        ];
    }
}
