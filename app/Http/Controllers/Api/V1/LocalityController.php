<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CountyResource;
use App\Http\Resources\LocalityResource;
use App\Models\County;
use App\Models\Locality;
use App\Services\CountyService;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalityController extends Controller
{
    public function __construct(
        protected LocalityService $localityService,
        protected CountyService $countyService
    ) {}

    public function index(Request $request): JsonResponse
    {

        // ------------------------------
        // 0. Validare query params
        // ------------------------------

        // Abbreviations are advertised in lowercase by the counties endpoint but
        // stored uppercase, so normalise before the exists check rather than
        // relying on the database collation to ignore case.
        $params = $request->query();

        if (is_string($params['county'] ?? null)) {
            $params['county'] = strtoupper($params['county']);
        }

        $validator = Validator::make($params, [
            // București is abbreviated "B", so the length is 1 or 2, not a fixed 2.
            'county' => ['required', 'string', 'between:1,2', 'exists:counties,abbr'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid query parameters.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // ------------------------------
        // 1. Fetch County & Localities
        // ------------------------------
        $countyCode = $params['county'];
        $county = County::where('abbr', $countyCode)->firstOrFail();
        $localities = $this->localityService->getByCountyWithParent($county);

        // ------------------------------
        // 2. Filtru TYPE (opțional)
        // ------------------------------
        if ($type = $request->query('type')) {
            $localities = $localities->where('type', $type)->values();
        }

        // ------------------------------
        // 3. SEARCH (opțional)
        // ------------------------------
        if ($search = $request->query('search')) {
            $search = mb_strtolower($search);

            $localities = $localities->filter(
                fn ($l) => str_contains($l['name_ascii'], $search)
            )->values();
        }

        // ------------------------------
        // 4. LIMIT (siguranță API public)
        // ------------------------------
        // $limit = min((int) $request->query('limit', 100), 500);
        // $localities = $localities->take($limit)->values();

        // ------------------------------
        // 5. RESPONSE
        // ------------------------------
        return response()->json([
            'data' => LocalityResource::collection($localities),
            'meta' => [
                'county' => [
                    'id' => $county->id,
                    'name' => $county->name,
                    'code' => $county->abbr,
                ],
                'total' => $localities->count(),
            ],
        ]);
    }

    public function show(string $siruta): JsonResponse
    {
        // A SIRUTA code is always numeric, so anything else is a path that was
        // never a locality - answer "not found" instead of letting the lookup
        // reach the database and blow up on a type mismatch.
        if (! ctype_digit($siruta)) {
            return $this->localityNotFound();
        }

        $locality = Locality::with(['parent', 'county'])
            ->where('siruta_code', (int) $siruta)
            ->first();

        if (! $locality) {
            return $this->localityNotFound();
        }

        return response()->json([
            'data' => new LocalityResource(
                $this->localityService->toResourceArray($locality)
            ),
            'meta' => [
                'county' => new CountyResource(
                    $this->countyService->resolve($locality->county->abbr)
                ),
            ],
        ]);
    }

    private function localityNotFound(): JsonResponse
    {
        return response()->json([
            'error' => 'Locality not found.',
            'status' => 404,
        ], 404);
    }
}
