<?php

namespace App\Http\Controllers\Api;

use App\Models\County;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountyController extends Controller
{
    public function __construct(
        protected CountyService $countyService,
        protected LocalityService $localityService
    ) {
    }

    public function index(): JsonResponse
    {
        $counties = $this->countyService->all();
        return response()->json([
            'data' => CountyResource::collection($counties),
            'meta' => [
                'total' => $counties->count(),
            ],
        ]);
    }

    public function localities(County $county): JsonResponse
    {
        $cacheKey = "api:v1:counties:{$county->abbr}:localities";

        $groups = Cache::remember(
            $cacheKey,
            now()->addDays(90),
            fn() => $this->localityService->getGroupedByCounty($county)
        );

        // asigurăm existența cheilor
        $municipii = $groups['municipii'] ?? collect();
        $orase = $groups['orase'] ?? collect();
        $comune = $groups['comune'] ?? collect();
        $sate = $groups['sate'] ?? collect();
        $sectoare = $groups['sectoare'] ?? collect();

        return response()->json([
            'data' => [
                'municipii' => LocalityResource::collection($municipii),
                'orase' => LocalityResource::collection($orase),
                'comune' => LocalityResource::collection($comune),
                'sate' => LocalityResource::collection($sate),
                'sectoare' => LocalityResource::collection($sectoare),
            ],
            'meta' => [
                'county' => new CountyResource($county),
                'counts' => [
                    'municipii' => $municipii->count(),
                    'orase' => $orase->count(),
                    'comune' => $comune->count(),
                    'sate' => $sate->count(),
                    'sectoare' => $sectoare->count(),
                ],
            ],
        ]);
    }
}
