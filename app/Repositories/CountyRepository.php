<?php
declare(strict_types=1);
namespace App\Repositories;

use App\Models\County;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CountyRepository extends BaseRepository
{
    public function all(): Collection
    {
        $data = Cache::rememberForever('counties.all', function (): mixed {
            return County::orderBy('name')
                ->get()
                ->map(fn($c): array => [
                    'id' => $c->id,
                    'siruta_code' => $c->siruta_code,
                    'name' => $c->name,
                    'name_ascii' => $c->name_ascii,
                    'abbr' => $c->abbr,
                    'region' => (int) $c->region?->value,
                ])
                ->all();
        });

        return collect($data);
    }


    public function findByIdOrAbbr(string $value): County
    {
        return County::where('id', $value)
            ->orWhere('abbr', strtoupper($value))
            ->firstOrFail();
    }
}
