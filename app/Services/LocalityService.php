<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\County;
use App\Http\Resources\LocalityResource;
use App\Repositories\LocalityRepository;

class LocalityService
{
    public function __construct(
        protected LocalityRepository $localities
    ) {
    }

    public function getByCounty(County $county)
    {
        return $this->localities->getByCounty($county);
    }

    public function getGroupedByCounty(County $county): array
    {
        $groups = $this->localities->getGroupedByCounty($county);

        return [
            'municipii' => LocalityResource::collection($groups['municipii']),
            'orase' => LocalityResource::collection($groups['orase']),
            'comune' => LocalityResource::collection($groups['comune']),
            'sate' => LocalityResource::collection($groups['sate']),
            'sectoare' => LocalityResource::collection($groups['sectoare']),
        ];
    }
}