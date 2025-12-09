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
            'parent' => $this->parent ? [
                'name' => $this->parent->name,
                'type' => $this->parent->type,
                'siruta_code' => $this->parent->siruta_code,
            ] : null,
            'name' => $this->cleanName($this->name),
            'name_ascii' => $this->name_ascii,
            'type' => $this->type,
            'postal_code' => $this->postal_code,
            'lat' => $this->lat,
            'lng' => $this->lng,
        ];
    }

    private function cleanName($name): array|string|null
    {
        return preg_replace('/^(Municipiul|Municipiu|Orasul|Oras|Orașul|Oraș|Comuna|Satul)\s+/iu', '', $name);
    }

}
