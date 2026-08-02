<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CountyQueryRequest;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityLiteResource;
use App\Models\County;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;

class CountyLocalitiesLiteController extends Controller
{
    public function __construct(
        private LocalityService $localityService,
        private CountyService $countyService,
    ) {}

    /**
     * The same listing addressed as /localities/lite?county=XX, the form the
     * public documentation advertises.
     */
    public function byQuery(CountyQueryRequest $request): JsonResponse
    {
        return $this->index(
            County::where('abbr', $request->countyAbbr())->firstOrFail()
        );
    }

    public function index(County $county): JsonResponse
    {
        $items = $this->localityService->getCountyLocalitiesLite(county: $county);

        $countyArray = $this->countyService->resolve($county->abbr);

        return response()->json([
            'data' => LocalityLiteResource::collection($items),
            'meta' => [
                'county' => new CountyResource($countyArray),
                'total' => $items->count(),
            ],
        ]);
    }
}
