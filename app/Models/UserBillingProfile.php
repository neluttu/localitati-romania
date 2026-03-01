<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBillingProfile extends Model
{
    use HasFactory;

    protected $table = 'user_billing_profiles';

    protected $fillable = [
        'user_id',
        'is_company',

        // PF
        'first_name',
        'last_name',

        // Firmă
        'company_name',
        'cif',
        'registration_number',
        'iban',
        'bank_name',

        // Adresă
        'country',
        'county',
        'city',
        'street',
        'postal_code',

        'phone',

        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAȚII
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPURI
    |--------------------------------------------------------------------------
    */

    public function scopeDefault($query): mixed
    {
        return $query->where('is_default', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS / HELPERS
    |--------------------------------------------------------------------------
    */

    public function getDisplayNameAttribute(): string
    {
        if ($this->type === 'company') {
            return $this->company_name ?: 'Fără nume firmă';
        }

        return trim(($this->first_name . ' ' . $this->last_name)) ?: 'Nume lipsă';
    }

    public function getFullAddressAttribute(): string
    {
        return trim("{$this->street}, {$this->city}, {$this->country}, {$this->postal_code}");
    }

    public function clearCompanyFields(): void
    {
        $this->update([
            'company_name' => null,
            'cif' => null,
            'registration_number' => null,
            'iban' => null,
            'bank_name' => null,
        ]);
    }

    public function isCompany(): bool
    {
        return $this->is_company;
    }


}
