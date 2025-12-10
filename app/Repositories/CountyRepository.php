<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\County;
use Illuminate\Support\Facades\Cache;

class CountyRepository extends BaseRepository
{
    public function all(): \Illuminate\Support\Collection
    {
        return Cache::rememberForever('counties.all', function () {
            return County::orderBy('name')->get();
        });
    }

    public function findByIdOrAbbr(string $value): County
    {
        return County::where('id', $value)
            ->orWhere('abbr', strtoupper($value))
            ->firstOrFail();
    }
}
