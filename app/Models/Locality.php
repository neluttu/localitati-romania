<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LocalityType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Locality extends Model
{
    use HasFactory;

    protected $fillable = [
        'siruta_code',
        'siruta_parent',
        'county_id',
        'name',
        'type',
        'postal_code',
        'lat',
        'lng',
        'name_ascii',
    ];

    protected $appends = ['display_name'];

    protected function casts(): array
    {
        return [
            'type' => LocalityType::class,
        ];
    }

    public function county(): BelongsTo
    {
        return $this->belongsTo(County::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Locality::class, 'siruta_parent', 'siruta_code');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Locality::class, 'siruta_parent', 'siruta_code');
    }

    /**
     * @param  Builder<Locality>  $query
     * @return Builder<Locality>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        // Expressed as CASE rather than MySQL's FIELD() so the ordering also runs
        // on SQLite, which the test suite uses. The 1-based positions and the 0
        // fallback reproduce FIELD() exactly, keeping unlisted types sorted first.
        $cases = '';

        foreach (LocalityType::orderList() as $index => $type) {
            $cases .= sprintf(' WHEN %d THEN %d', $type, $index + 1);
        }

        return $query
            ->orderByRaw("CASE type{$cases} ELSE 0 END")
            ->orderBy('type');
    }

    public function getDisplayNameAttribute(): string
    {
        return preg_replace('/^(Municipiul|Municipiu|Orașul|Oraș|Comuna|Satul|Sat)\s+/iu', '', $this->name);
    }
}
