<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocalityResource;
use App\Models\County;
use App\Services\LocalityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocalityController extends Controller
{
    public function __construct(
        protected LocalityService $localityService
    ) {}

    public function index(Request $request): JsonResponse
    {

        // ------------------------------
        // 0. Validare query params
        // ------------------------------

        $validator = Validator::make($request->query(), [
            'county' => ['required', 'string', 'size:2', 'exists:counties,abbr'],
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
        $countyCode = strtoupper($request->query('county'));
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
}
