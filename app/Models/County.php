<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\DevelopmentRegion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    /** @use HasFactory<\Database\Factories\CountyFactory> */
    use HasFactory;

    protected $fillable = ['siruta_code', 'name', 'code', 'region', 'abbr', 'slug', 'name_ascii'];

    protected $casts = [
        'siruta_code' => 'integer',
        'code' => 'integer',
        'region' => DevelopmentRegion::class,
    ];

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }

    public function getRouteKeyName(): string
    {
        return 'abbr';
    }
}
