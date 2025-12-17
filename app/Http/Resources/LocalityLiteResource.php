<?php
declare(strict_types=1);
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalityLiteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int) $this['id'],
            'siruta_code' => $this['siruta_code'],
            'name' => $this['name'],
            'name_ascii' => $this['name_ascii'],
            'postal_code' => $this['postal_code'],
        ];
    }
}

