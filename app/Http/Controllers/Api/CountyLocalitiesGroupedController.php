<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\County;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\CountyService;
use App\Services\LocalityService;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;

class CountyLocalitiesGroupedController extends Controller
{
    public function __construct(
        private LocalityService $localityService,
        private CountyService $countyService,
    ) {
    }

    public function index(County $county): JsonResponse
    {
        $groups = $this->localityService->getGroupedByCounty($county);
        $countyArray = $this->countyService->resolve($county->abbr);

        return response()->json([
            'data' => [
                'municipii' => LocalityResource::collection($groups['municipii'] ?? collect()),
                'orase' => LocalityResource::collection($groups['orase'] ?? collect()),
                'comune' => LocalityResource::collection($groups['comune'] ?? collect()),
                'sate' => LocalityResource::collection($groups['sate'] ?? collect()),
                'sectoare' => LocalityResource::collection($groups['sectoare'] ?? collect()),
            ],
            'meta' => [
                'county' => new CountyResource($countyArray),
                'counts' => [
                    'municipii' => ($groups['municipii'] ?? collect())->count(),
                    'orase' => ($groups['orase'] ?? collect())->count(),
                    'comune' => ($groups['comune'] ?? collect())->count(),
                    'sate' => ($groups['sate'] ?? collect())->count(),
                    'sectoare' => ($groups['sectoare'] ?? collect())->count(),
                ],
            ],
        ]);
    }
}
