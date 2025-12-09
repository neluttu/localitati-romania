<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'siruta_code' => $this->siruta_code,
            'name' => $this->name,
            'name_ascii' => $this->name_ascii,
            'type' => $this->type,
            'postal_code' => $this->postal_code,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }
}
