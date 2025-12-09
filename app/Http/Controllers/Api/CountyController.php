<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Models\County;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountyController extends Controller
{
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
        $cacheKey = "county_{$county->id}_localities";

        $localities = cache()->rememberForever($cacheKey, function () use ($county) {
            return $county->localities()
                ->whereIn('type', [1, 2, 3, 4, 5, 22, 23])
                ->ordered()
                ->get();

        });

        return LocalityResource::collection($localities);
    }

    public function localitiesGrouped(County $county): JsonResponse
    {
        $cacheKey = "county_{$county->id}_localities_grouped";

        $data = cache()->rememberForever($cacheKey, function () use ($county) {

            $localities = $county->localities()->ordered()->get();

            return [
                'municipii' => $localities->whereIn('type', [1, 4])->values(),
                'orase' => $localities->whereIn('type', [2, 5])->values(),
                'comune' => $localities->where('type', 3)->values(),
                'sate' => $localities->whereIn('type', [22, 23])->values(),
            ];
        });

        return response()->json($data);
    }



}
