<?php

namespace App\Http\Resources;

use App\Enums\DevelopmentRegion;
use Illuminate\Http\Resources\Json\JsonResource;

class CountyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'siruta_code' => $this->siruta_code,
            'name' => $this->name,
            'abbr' => $this->abbr,
            'slug' => $this->slug,

            'region' => [
                'id' => $this->region->value,
                'label' => $this->region->label(),
            ],
        ];
    }
}
