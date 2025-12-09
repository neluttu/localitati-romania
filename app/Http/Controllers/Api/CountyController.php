<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Models\County;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use App\Services\LocalityService;

class CountyController extends Controller
{
    public function __construct(protected LocalityService $localityService)
    {

    }

    public function index(): AnonymousResourceCollection
    {
        // Lista județelor nu se schimbă → cache forever
        $counties = cache()->rememberForever('api_counties', function () {
            return County::orderBy('name')->get();
        });

        // Returnăm collection de resources
        return CountyResource::collection($counties);
    }

    public function localities(County $county): AnonymousResourceCollection
    {
        $localities = $this->localityService->getLocalities($county);

        return LocalityResource::collection($localities);
    }

    public function localitiesGrouped(County $county): JsonResponse
    {
        $data = $this->localityService->getLocalitiesGrouped($county);

        return response()->json($data);
    }
}
