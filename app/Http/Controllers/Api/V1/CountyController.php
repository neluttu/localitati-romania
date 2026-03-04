<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;
use App\Models\County;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function __construct(
        protected CountyService $countyService,
        protected LocalityService $localityService
    ) {}

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

    public function show(string $county): JsonResponse
    {
        $countyModel = County::where('abbr', strtoupper($county))->firstOrFail();
        $countyArray = $this->countyService->resolve($countyModel->abbr);

        return response()->json([
            'data' => new CountyResource($countyArray),
            'meta' => [
                'localities_endpoint' => route('api.localities', ['county' => strtolower($countyModel->abbr)], false),
                'localities_endpoint_lite' => route('api.localities.lite', ['county' => strtolower($countyModel->abbr)], false),
                'localities_endpoint_grouped' => route('api.localities.grouped', ['county' => strtolower($countyModel->abbr)], false),
            ],
        ]);
    }

    public function localities(County $county, Request $request): JsonResponse
    {
        // -------------------------------------------------
        // 1. DACĂ EXISTĂ ?type → MOD FILTRAT
        // -------------------------------------------------
        if ($request->filled('type')) {
            $type = strtoupper($request->query('type'));

            // luăm toate localitățile din județ (flat, cu parent)
            $localities = $this->localityService
                ->getByCountyWithParent($county);

            $filtered = $this->localityService
                ->filterBySingleType($localities, $type);

            $countyArray = $this->countyService->resolve($county->abbr);

            return response()->json([
                'data' => LocalityResource::collection($filtered),
                'meta' => [
                    'county' => new CountyResource($countyArray),
                    'type' => $type,
                    'total' => $filtered->count(),
                ],
            ]);
        }

        $groups = $this->localityService->getGroupedByCountyCached($county);

        // asigurăm existența cheilor
        $municipii = $groups['municipii'] ?? collect();
        $orase = $groups['orase'] ?? collect();
        $comune = $groups['comune'] ?? collect();
        $sate = $groups['sate'] ?? collect();
        $sectoare = $groups['sectoare'] ?? collect();

        $countyArray = $this->countyService->resolve($county->abbr);

        return response()->json([
            'data' => [
                'municipii' => LocalityResource::collection($municipii),
                'orase' => LocalityResource::collection($orase),
                'comune' => LocalityResource::collection($comune),
                'sate' => LocalityResource::collection($sate),
                'sectoare' => LocalityResource::collection($sectoare),
            ],
            'meta' => [
                'county' => new CountyResource($countyArray),
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
