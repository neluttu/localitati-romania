<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\DevelopmentRegion;
use App\Enums\LocalityType;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class LookupController extends Controller
{
    /**
     * The SIRUTA classification codes, ordered so a dropdown built straight
     * from the response reads from municipality down to village.
     */
    public function localityTypes(): JsonResponse
    {
        $types = collect(LocalityType::cases())
            ->reject(fn (LocalityType $type): bool => $type === LocalityType::UNKNOWN)
            ->sortBy(fn (LocalityType $type): int => $type->sortOrder())
            ->map(fn (LocalityType $type): array => [
                'id' => $type->value,
                'code' => $type->name,
                'label' => $type->label(),
                'group' => $type->group(),
            ])
            ->values();

        return response()->json([
            'data' => $types,
            'meta' => [
                'total' => $types->count(),
            ],
        ]);
    }

    /**
     * Romania's eight development regions, each with the county abbreviations
     * it covers, so a client can map a county to its region without a second call.
     */
    public function regions(): JsonResponse
    {
        $regions = collect(DevelopmentRegion::cases())
            ->map(fn (DevelopmentRegion $region): array => [
                'id' => $region->value,
                'code' => $region->name,
                'label' => $region->label(),
                'counties' => $region->counties(),
            ])
            ->values();

        return response()->json([
            'data' => $regions,
            'meta' => [
                'total' => $regions->count(),
            ],
        ]);
    }
}
