<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CountyQueryRequest;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;
use App\Models\County;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;

class CountyLocalitiesGroupedController extends Controller
{
    public function __construct(
        private LocalityService $localityService,
        private CountyService $countyService,
    ) {}

    /**
     * The same grouping addressed as /localities/grouped?county=XX, the form
     * the public documentation advertises.
     */
    public function byQuery(CountyQueryRequest $request): JsonResponse
    {
        return $this->index(
            County::where('abbr', $request->countyAbbr())->firstOrFail()
        );
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
