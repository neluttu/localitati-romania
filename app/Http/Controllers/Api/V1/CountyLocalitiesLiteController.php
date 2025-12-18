<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api\V1;

use App\Models\County;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityLiteResource;

class CountyLocalitiesLiteController extends Controller
{
    public function __construct(
        private LocalityService $localityService,
        private CountyService $countyService,
    ) {
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

