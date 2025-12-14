<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\County;
use App\Enums\LocalityType;
use Illuminate\Support\Collection;
use App\Repositories\LocalityRepository;

class LocalityService
{
    public function __construct(
        protected LocalityRepository $localities
    ) {
    }

    public function getByCounty(County $county): Collection
    {
        return $this->localities->ByCounty($county);
    }

    public function getByCountyWithParent(County $county): Collection
    {
        return $this->attachParent(
            $this->getByCounty($county)
        );
    }


    public function getGroupedByCounty(County $county): array
    {
        $localities = $this->attachParent(
            $this->getByCounty($county)
        );

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


    private function attachParent(Collection $localities): Collection
    {
        $index = $localities->keyBy('siruta_code');

        return $localities
            ->map(function ($loc) use ($index) {
                // display_name mereu
                $loc['display_name'] = $this->cleanName($loc['name']);

                if (
                    !empty($loc['siruta_parent']) &&
                    isset($index[$loc['siruta_parent']])
                ) {
                    $parent = $index[$loc['siruta_parent']];

                    $parentName = $this->cleanName($parent['name']);

                    // 🔥 REGULA IMPORTANTĂ:
                    // dacă localitatea are același nume ca părintele → o eliminăm
                    if ($loc['display_name'] === $parentName) {
                        return null;
                    }

                    $loc['parent'] = [
                        'name' => $parentName,
                        'type' => $parent['type'],
                        'siruta_code' => $parent['siruta_code'],
                    ];
                } else {
                    $loc['parent'] = null;
                }

                return $loc;
            })
            ->filter()   // elimină null-urile (ex: Reghin componentă)
            ->values();
    }


    private function cleanName(string $name): string
    {
        return preg_replace(
            '/^(Municipiul|Municipiu|Orașul|Oraș|Comuna|Satul|Sat)\s+/iu',
            '',
            $name
        );
    }

    public function filterBySingleType(Collection $localities, string $type): Collection
    {
        if (!defined(LocalityType::class . '::' . $type)) {
            abort(400, "Invalid locality type: {$type}");
        }

        $enum = constant(LocalityType::class . '::' . $type)->value;

        return $localities
            ->where('type', $enum)
            ->values();
    }

}