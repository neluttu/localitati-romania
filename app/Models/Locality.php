<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locality extends Model
{
    protected $fillable = [
        'siruta_code',
        'county_id',
        'name',
        'type',
        'postal_code',
        'lat',
        'lng',
        'name_ascii',
    ];

    public function county()
    {
        return $this->belongsTo(County::class);
    }

    public function scopeOrdered($query): mixed
    {
        return $query
            ->orderByRaw("
            CASE 
                WHEN type = 1 THEN 1
                WHEN type = 4 THEN 2
                WHEN type = 2 THEN 3
                WHEN type = 5 THEN 4
                WHEN type = 3 THEN 5
                WHEN type = 22 THEN 6
                WHEN type = 23 THEN 7
                ELSE 99
            END
        ")
            ->orderBy('name');
    }

}

