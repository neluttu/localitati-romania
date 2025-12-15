<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\County;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\LocalityResource;

class CountyLocalitiesController extends Controller
{
    public function __construct(private LocalityService $localityService)
    {
    }

    public function index(County $county): JsonResponse
    {
        $items = $this->localityService->getCountyLocalities($county);

        return response()->json([
            'data' => LocalityResource::collection($items),
            'meta' => [
                'county' => [
                    'id' => $county->id,
                    'name' => $county->name,
                    'code' => $county->abbr,
                ],
                'total' => $items->count(),
            ],
        ]);
    }
}
