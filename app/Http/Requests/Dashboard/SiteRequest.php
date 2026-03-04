<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'domain' => [
                'required',
                'string',
                'max:255',
                'regex:/^(\*\.)?[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9-]*[a-zA-Z0-9])?)*$/',
                Rule::unique('sites', 'domain')->where(function ($query) {
                    return $query->where('user_id', $this->user()->id);
                }),
            ],
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
            'domain.unique' => 'Ai deja un site înregistrat cu acest domeniu.',
        ];
    }
}
