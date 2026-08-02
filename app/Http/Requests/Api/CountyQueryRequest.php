<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CountyQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Abbreviations are advertised in lowercase by the counties endpoint but
     * stored uppercase, so normalise before the exists check rather than
     * relying on the database collation to ignore case.
     */
    protected function prepareForValidation(): void
    {
        $county = $this->query('county');

        if (is_string($county)) {
            $this->merge(['county' => strtoupper($county)]);
        }
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            // București is abbreviated "B", so the length is 1 or 2, not a fixed 2.
            'county' => ['required', 'string', 'between:1,2', 'exists:counties,abbr'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'county.required' => 'Parametrul county este obligatoriu.',
            'county.between' => 'Abrevierea județului are unul sau două caractere.',
            'county.exists' => 'Județul specificat nu există.',
        ];
    }

    public function countyAbbr(): string
    {
        return strtoupper((string) $this->validated('county'));
    }
}
