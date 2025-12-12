<?php

namespace App\Http\Controllers\Api;

use App\Models\County;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
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

    public function index(): AnonymousResourceCollection
    {
        return CountyResource::collection(
            resource: $this->countyService->all()
        );
    }

    public function localities(County $county): JsonResponse
    {
        $groups = $this->localityService->getGroupedByCounty($county);

        return response()->json([
            'municipii' => LocalityResource::collection($groups['municipii']),
            'orase' => LocalityResource::collection($groups['orase']),
            'comune' => LocalityResource::collection($groups['comune']),
            'sate' => LocalityResource::collection($groups['sate']),
            'sectoare' => LocalityResource::collection($groups['sectoare']),
        ]);
    }
}
