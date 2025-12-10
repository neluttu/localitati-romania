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
            $this->countyService->all()
        );
    }

    public function localities(County $county): AnonymousResourceCollection
    {
        return LocalityResource::collection(
            $this->localityService->getByCounty($county)
        );
    }

    public function localitiesGrouped(County $county): JsonResponse
    {
        return response()->json(
            $this->localityService->getGroupedByCounty($county)
        );
    }
}
