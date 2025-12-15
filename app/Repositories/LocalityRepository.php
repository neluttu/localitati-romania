<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\County;
use App\Enums\LocalityType;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LocalityRepository extends BaseRepository
{
    public function byCounty(County $county): Collection
    {
        // return Cache::rememberForever(
        //     "api:v1:county:{$county->abbr}:localities",
        //     fn(): mixed => $county->localities()
        //         ->ordered()
        //         ->get()
        //         ->map(function ($l) {
        //             $arr = $l->toArray();
        //             unset($arr['created_at'], $arr['updated_at']);
        //             return $arr;
        //         })
        // );


        return $county->localities()
            ->ordered()
            ->get()
            ->map(fn($l) => collect($l->toArray())->except([
                'created_at',
                'updated_at',
            ])->all());
    }


    protected function groupLocalities(Collection $localities): array
    {
        return [
            'municipii' => $localities->whereIn('type', [
                LocalityType::MUNICIPIU_RESEDINTA,
                LocalityType::MUNICIPIU,
            ])->values(),

            'orase' => $localities->whereIn('type', [
                LocalityType::ORAS,
                LocalityType::ORAS_RESEDINTA,
            ])->values(),

            'comune' => $localities->where(
                'type',
                LocalityType::COMUNA
            )
                ->values(),

            'sate' => $localities->whereIn('type', [
                LocalityType::SAT,
                LocalityType::SAT_RESEDINTA_COMUNA,
            ])->values(),

            'sectoare' => $localities->where('type', LocalityType::SECTOR)->values(),
        ];
    }

    public function getGroupedByCounty(County $county): array
    {
        return $this->groupLocalities(
            $this->byCounty($county)
        );
    }
}
