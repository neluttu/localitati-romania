<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'code' => $this['abbr'],
            'abbr' => $this['abbr'],
            'siruta_code' => $this['siruta_code'],
            'region' => $this['region'],
        ];
    }
}
