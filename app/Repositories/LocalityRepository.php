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
        $data = Cache::rememberForever(
            "api:v1:county:{$county->abbr}:localities",
            fn(): mixed => $county->localities()
                ->ordered()
                ->get()
                ->map(fn($l): array => [
                    'id' => (int) $l->id,

                    // SIRUTA
                    'siruta_code' => (int) $l->siruta_code,
                    'siruta_parent' => $l->siruta_parent
                        ? (int) $l->siruta_parent
                        : null,

                    // names
                    'display_name' => $l->display_name,
                    'name_ascii' => $l->name_ascii,

                    // type (ENUM → int)
                    'type' => $l->type instanceof LocalityType
                        ? $l->type->value
                        : (int) $l->type,

                    // extra (primitive only)
                    'postal_code' => $l->postal_code,
                    'lat' => $l->lat !== null ? (float) $l->lat : null,
                    'lng' => $l->lng !== null ? (float) $l->lng : null,
                ])
                ->all()
        );

        return collect($data);
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
