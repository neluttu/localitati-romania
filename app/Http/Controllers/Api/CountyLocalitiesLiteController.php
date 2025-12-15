<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Models\County;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocalityLiteResource;

class CountyLocalitiesLiteController extends Controller
{
    public function __construct(
        private LocalityService $localityService
    ) {
    }

    public function index(County $county): JsonResponse
    {
        $items = $this->localityService->getCountyLocalitiesLite($county);

        return response()->json([
            'data' => LocalityLiteResource::collection($items),
            'meta' => [
                'county' => $county->abbr,
                'total' => $items->count(),
            ],
        ]);
    }
}

