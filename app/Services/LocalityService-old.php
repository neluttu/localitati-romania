<?php

// namespace App\Services;

// use App\Models\County;
// use App\Http\Resources\LocalityResource;
// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Cache;

// class LocalityService-old-old
// {
//     public function getLocalities(County $county): Collection
//     {
//         $cacheKey = "county_{$county->id}_localities_raw";

//         // Cache DOAR modelele brute
//         $localities = Cache::rememberForever($cacheKey, function () use ($county) {
//             return $county->localities()
//                 ->with('parent')
//                 ->ordered()
//                 ->get();
//         });

//         return $localities;
//     }

//     public function getLocalitiesGrouped(County $county): array
//     {
//         $localities = $this->getLocalities($county);

//         return [
//             'municipii' => LocalityResource::collection(
//                 $localities->whereIn('type', [1, 4])->values()
//             ),
//             'orase' => LocalityResource::collection(
//                 $localities->whereIn('type', [2, 5])->values()
//             ),
//             'comune' => LocalityResource::collection(
//                 $localities->where('type', 3)->values()
//             ),
//             'sate' => LocalityResource::collection(
//                 $localities->whereIn('type', [22, 23])->values()
//             ),
//         ];
//     }

//     public function clearCache(County $county): void
//     {
//         Cache::forget("county_{$county->id}_localities_raw");
//     }
// }
