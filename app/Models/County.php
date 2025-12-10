<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class County extends Model
{
    protected $fillable = ['siruta_code', 'name', 'code', 'region', 'abbr', 'slug', 'name_ascii'];

    protected $casts = [
        'siruta_code' => 'integer',
        'code' => 'integer',
    ];

    protected $withCount = ['localities'];

    public function localities(): HasMany
    {
        return $this->hasMany(Locality::class);
    }

    public function getRouteKeyName(): string
    {
        return 'abbr';
    }
}

