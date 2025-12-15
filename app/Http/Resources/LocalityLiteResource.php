<?php
declare(strict_types=1);
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalityLiteResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'siruta_code' => $this['siruta_code'],
            'name' => $this['name'],
            'parent' => $this['parent'],
            'postal_code' => $this['postal_code'],
        ];
    }
}

