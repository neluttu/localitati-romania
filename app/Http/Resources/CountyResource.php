<?php
declare(strict_types=1);
namespace App\Http\Resources;

use App\Enums\DevelopmentRegion;
use Illuminate\Http\Resources\Json\JsonResource;

class CountyResource extends JsonResource
{
    public function toArray($request): array
    {
        $region = DevelopmentRegion::from((int) $this['region']);

        return [
            'id' => $this['id'],
            'siruta_code' => $this['siruta_code'],
            'name' => $this['name'],
            'name_ascii' => $this['name_ascii'],
            'abbr' => $this['abbr'],

            'region' => [
                'id' => $region->value,
                'label' => $region->label(),
            ],
        ];
    }
}
