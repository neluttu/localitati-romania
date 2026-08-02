<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LocalityType;
use App\Models\County;
use App\Models\Locality;
use App\Repositories\LocalityRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LocalityService
{
    public function __construct(
        protected LocalityRepository $localities
    ) {}

    // ========== FETCH ==========
    public function getByCounty(County $county): Collection
    {
        return $this->localities->ByCounty($county);
    }

    // ========== ENRICH ==========
    public function getByCountyWithParent(County $county): Collection
    {
        return $this->attachParent(
            $this->getByCounty($county)
        );
    }

    public function getGroupedByCountyCached(County $county): array
    {
        return Cache::remember(
            "api:v1:counties:{$county->abbr}:localities-grouped",
            now()->addDays(90),
            fn () => $this->getGroupedByCounty($county)
        );
    }

    public function getGroupedByCounty(County $county): array
    {
        $localities = $this->getByCountyWithParent($county);

        return [
            'municipii' => $localities->whereIn(
                'type',
                [
                    LocalityType::MUNICIPIU_RESEDINTA->value,
                    LocalityType::MUNICIPIU->value,
                ]
            )->values(),

            'orase' => $localities->whereIn(
                'type',
                [
                    LocalityType::ORAS->value,
                ]
            )->values(),

            'comune' => $localities->whereIn(
                'type',
                [
                    LocalityType::COMUNA->value,
                ]
            )->values(),

            'sate' => $localities->whereIn(
                'type',
                [
                    // sate clasice
                    LocalityType::SAT_RESEDINTA_COMUNA->value,
                    LocalityType::SAT->value,

                    // sate / componente aparținătoare de municipiu
                    LocalityType::COMPONENTA_RESEDINTA_MUNICIPIU->value,
                    LocalityType::COMPONENTA_MUNICIPIU->value,
                    LocalityType::SAT_APARTINATOR_MUNICIPIU->value,

                    // sate / componente aparținătoare de oraș
                    LocalityType::COMPONENTA_RESEDINTA_ORAS->value,
                    LocalityType::COMPONENTA_ORAS->value,
                    LocalityType::SAT_APARTINATOR_ORAS->value,
                ]
            )->values(),

            'sectoare' => $localities->whereIn(
                'type',
                [
                    LocalityType::SECTOR->value,
                ]
            )->values(),
        ];
    }

    public function getCountyLocalities(County $county): Collection
    {
        $localities = $this->getByCountyWithParent($county);

        return $localities
            ->whereIn('type', [
                // municipii / orașe – componente locuibile
                LocalityType::COMPONENTA_RESEDINTA_MUNICIPIU->value,
                LocalityType::COMPONENTA_MUNICIPIU->value,
                LocalityType::SAT_APARTINATOR_MUNICIPIU->value,

                LocalityType::COMPONENTA_RESEDINTA_ORAS->value,
                LocalityType::COMPONENTA_ORAS->value,
                LocalityType::SAT_APARTINATOR_ORAS->value,

                // sate
                LocalityType::SAT_RESEDINTA_COMUNA->value,
                LocalityType::SAT->value,

                // București
                LocalityType::SECTOR->value,
            ])
            ->values();
    }

    public function getCountyLocalitiesLite(County $county): Collection
    {
        return Cache::rememberForever(
            "api:v1:county:{$county->abbr}:localities-lite",
            fn (): Collection => $this->getCountyLocalities($county)
                ->map(fn ($l): array => [
                    'id' => (int) $l['id'], // ✅ adăugat
                    'siruta_code' => (int) $l['siruta_code'],
                    'name' => $l['display_name'],
                    'name_ascii' => $l['name_ascii'], // dacă Resource îl cere
                    'postal_code' => $l['postal_code'] !== '000000' ? $l['postal_code'] : null,
                ])
        );
    }

    /**
     * Map one model onto the same array shape the cached county listings use,
     * so a locality fetched on its own renders identically to a listed one.
     * Read straight from the model rather than the county cache: a single
     * lookup must not depend on a 90-day cache entry being warm and current.
     *
     * @return array<string, mixed>
     */
    public function toResourceArray(Locality $locality): array
    {
        $parent = $locality->parent;

        return [
            'id' => (int) $locality->id,
            'siruta_code' => (int) $locality->siruta_code,
            'siruta_parent' => $locality->siruta_parent !== null
                ? (int) $locality->siruta_parent
                : null,

            'display_name' => $locality->display_name,
            'name_ascii' => $locality->name_ascii,

            'type' => $this->typeValue($locality),

            'postal_code' => $locality->postal_code,
            'lat' => $locality->lat !== null ? (float) $locality->lat : null,
            'lng' => $locality->lng !== null ? (float) $locality->lng : null,

            'parent' => $parent ? [
                'siruta_code' => (int) $parent->siruta_code,
                'name' => $parent->display_name,
                'type' => $this->typeValue($parent),
            ] : null,
        ];
    }

    private function typeValue(Locality $locality): int
    {
        return $locality->type instanceof LocalityType
            ? $locality->type->value
            : (int) $locality->type;
    }

    private function attachParent(Collection $localities): Collection
    {
        $index = $localities->keyBy('siruta_code');

        return $localities
            ->map(function ($loc) use ($index) {

                $loc['display_name'] = $loc['display_name'] ?? '';

                if (
                    ! empty($loc['siruta_parent']) &&
                    isset($index[$loc['siruta_parent']])
                ) {
                    $parent = $index[$loc['siruta_parent']];

                    $loc['parent'] = [
                        'siruta_code' => (int) $parent['siruta_code'],
                        'name' => $parent['display_name'],
                        'type' => (int) $parent['type'],
                    ];
                } else {
                    $loc['parent'] = null;
                }

                return $loc;
            })
            ->values();
    }

    public function filterBySingleType(Collection $localities, string $type): Collection
    {
        if (! defined(LocalityType::class.'::'.$type)) {
            abort(400, "Invalid locality type: {$type}");
        }

        $enum = constant(LocalityType::class.'::'.$type)->value;

        return $localities
            ->where('type', $enum)
            ->values();
    }
}
