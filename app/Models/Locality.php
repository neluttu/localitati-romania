<?php
declare(strict_types=1);
namespace App\Models;

use App\Enums\LocalityType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Locality extends Model
{
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

    protected $casts = [
        'type' => LocalityType::class,
        'region' => DevelopmentRegion::class,
    ];


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


    public function scopeOrdered($query): mixed
    {
        $order = implode(',', LocalityType::orderList());

        return $query
            ->orderByRaw("FIELD(type, $order)")
            ->orderBy('type');
        // ->orderBy('name');
    }


    public function getDisplayNameAttribute(): string
    {
        return preg_replace('/^(Municipiul|Municipiu|Orașul|Oraș|Comuna|Satul|Sat)\s+/iu', '', $this->name);
    }


}

