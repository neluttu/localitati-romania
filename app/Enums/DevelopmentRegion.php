<?php
declare(strict_types=1);
namespace App\Enums;

enum DevelopmentRegion: int
{
    case NORD_EST = 1;
    case SUD_EST = 2;
    case SUD = 3;
    case SUD_VEST = 4;
    case VEST = 5;
    case NORD_VEST = 6;
    case CENTRU = 7;
    case BUCURESTI_ILFOV = 8;

    public function label(): string
    {
        return match ($this) {
            self::NORD_EST => 'Nord-Est',
            self::SUD_EST => 'Sud-Est',
            self::SUD => 'Sud',
            self::SUD_VEST => 'Sud-Vest',
            self::VEST => 'Vest',
            self::NORD_VEST => 'Nord-Vest',
            self::CENTRU => 'Centru',
            self::BUCURESTI_ILFOV => 'București-Ilfov',
        };
    }

    public function counties(): array
    {
        return match ($this) {
            self::NORD_EST => ['BT', 'NT', 'IS', 'BC', 'SV', 'VS'],
            self::SUD_EST => ['BR', 'BZ', 'GL', 'TL', 'CT', 'VN'],
            self::SUD => ['AG', 'CL', 'DB', 'GR', 'IL', 'PH', 'TR'],
            self::SUD_VEST => ['DJ', 'GJ', 'MH', 'OT', 'VL'],
            self::VEST => ['AR', 'CS', 'TM', 'HD'],
            self::NORD_VEST => ['BH', 'BN', 'CJ', 'MM', 'SM', 'SJ'],
            self::CENTRU => ['AB', 'BV', 'CV', 'HR', 'MS', 'SB'],
            self::BUCURESTI_ILFOV => ['B', 'IF'],
        };
    }

    public static function fromCounty(string $countyCode): self
    {
        foreach (self::cases() as $region) {
            if (in_array($countyCode, $region->counties(), true)) {
                return $region;
            }
        }

        throw new \InvalidArgumentException("County {$countyCode} not mapped to any region.");
    }
}
