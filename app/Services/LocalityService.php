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
                    LocalityType::SAT_RESEDINTA_COMUNA->value,
                    LocalityType::SAT->value,
                    LocalityType::SAT_APARTINATOR_MUNICIPIU->value,
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

        return $localities->map(function ($loc) use ($index) {
            if (
                !empty($loc['siruta_parent']) &&
                isset($index[$loc['siruta_parent']])
            ) {
                $parent = $index[$loc['siruta_parent']];

                $loc['parent'] = [
                    'name' => $this->cleanName($parent['name']),
                    'type' => $parent['type'],
                    'siruta_code' => $parent['siruta_code'],
                ];
            } else {
                $loc['parent'] = null;
            }

            $loc['display_name'] = $this->cleanName($loc['name']);

            return $loc;
        });
    }

    private function cleanName(string $name): string
    {
        return preg_replace(
            '/^(Municipiul|Municipiu|Orașul|Oraș|Comuna|Satul|Sat)\s+/iu',
            '',
            $name
        );
    }
}