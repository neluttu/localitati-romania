<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalityResource extends JsonResource
{
    public function toArray($request): array
    {

        return [
            'id' => (int) $this['id'],
            'siruta_code' => (int) $this['siruta_code'],

            'name' => $this['display_name'],
            'name_ascii' => $this['name_ascii'],

            'type' => $this['type'],
            'postal_code' => $this['postal_code'] ?: null,

            'lat' => isset($this['lat']) ? (float) $this['lat'] : null,
            'lng' => isset($this['lng']) ? (float) $this['lng'] : null,

            'parent' => isset($this['parent']) && $this['parent']
                ? [
                    'siruta_code' => (int) $this['parent']['siruta_code'],
                    'name' => $this['parent']['name'],
                    'type' => (int) $this['parent']['type'],
                ]
                : null,
        ];
    }

    private function cleanName($name): array|string|null
    {
        return preg_replace('/^(Municipiul|Municipiu|Orasul|Oras|Orașul|Oraș|Comuna|Satul)\s+/iu', '', $name);
    }

}