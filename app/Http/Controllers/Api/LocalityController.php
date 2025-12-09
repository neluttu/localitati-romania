<?php

namespace App\Http\Controllers\Api;

use App\Models\Locality;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocalityController
{
    // GET /localities?county=AB
    public function index(Request $request): JsonResponse
    {
        $query = Locality::query()
            ->select(
                'id',
                'name',
                'county_id',
                'postal_code',
                'siruta',
                'type',
                'type_label',
                'rank',
                'population',
                'parent_locality',
                'region',
                'lat',
                'lng'
            );

        if ($request->filled('county')) {
            $query->whereHas('county', function ($q) use ($request) {
                $q->where('code', $request->county);
            });
        }

        $localities = $query
            ->orderByRaw("CASE 
            WHEN rank = 'I' THEN 1
            WHEN rank = 'II' THEN 2
            WHEN rank = 'III' THEN 3
            ELSE 4
        END")
            ->orderBy('population', 'desc')
            ->orderBy('name')
            ->get();

        return response()->json($localities);
    }


    // GET /localities/{id}
    public function show(int $id): JsonResponse
    {
        $locality = Locality::with('county')->findOrFail($id);

        return response()->json($locality);
    }

    // GET /search?q=alba
    public function search(Request $request): JsonResponse
    {
        $q = $request->q;

        if (!$q) {
            return response()->json([]);
        }

        $results = Locality::where('name', 'like', "%{$q}%")
            ->orderBy('name')
            ->limit(20)
            ->get(['id', 'name', 'county_id', 'postal_code']);

        return response()->json($results);
    }

    // GET /localities-by-county
    public function grouped(): JsonResponse
    {
        $localities = Locality::select('id', 'name', 'county_id')
            ->orderBy('name')
            ->get()
            ->groupBy('county_id');

        return response()->json($localities);
    }
}
