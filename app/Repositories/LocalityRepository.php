<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\County;
use App\Models\Locality;
use App\Enums\LocalityType;
use Illuminate\Support\Facades\Cache;

class LocalityRepository extends BaseRepository
{
    public function getByCounty(County $county)
    {
        return Cache::rememberForever("county_{$county->id}_localities", function () use ($county) {
            return $county->localities()
                ->with('parent')
                ->ordered()
                ->get();
        });
    }

    public function getGroupedByCounty(County $county): array
    {
        $localities = $this->getByCounty($county);

        return [
            'municipii' => $localities->whereIn('type', [
                LocalityType::MUNICIPIU_RESEDINTA,
                LocalityType::MUNICIPIU,
            ])->values(),

            'orase' => $localities->whereIn('type', [
                LocalityType::ORAS,
                LocalityType::ORAS_RESEDINTA,
            ])->values(),

            'comune' => $localities->where('type', LocalityType::COMUNA)->values(),

            'sate' => $localities->whereIn('type', [
                LocalityType::SAT,
                LocalityType::SAT_RESEDINTA_COMUNA,
            ])->values(),

            'sectoare' => $localities->whereIn('type', [
                LocalityType::SECTOR,
            ])->values(),
        ];
    }
}
