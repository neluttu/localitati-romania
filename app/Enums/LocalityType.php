<?php

namespace App\Enums;

enum LocalityType: int
{
    case MUNICIPIU_RESEDINTA = 1;
    case ORAS = 2;
    case COMUNA = 3;
    case MUNICIPIU = 4;
    case ORAS_RESEDINTA = 5;
    case SECTOR = 6;
    case COMPONENTA_RESEDINTA_MUNICIPIU = 9;
    case COMPONENTA_MUNICIPIU = 10;
    case SAT_APARTINATOR_MUNICIPIU = 11;
    case COMPONENTA_RESEDINTA_ORAS = 17;
    case COMPONENTA_ORAS = 18;
    case SAT_APARTINATOR_ORAS = 19;
    case SAT_RESEDINTA_COMUNA = 22;
    case SAT = 23;

    case UNKNOWN = 99;

    public function label(): string
    {
        return match ($this) {
            self::MUNICIPIU_RESEDINTA => 'Municipiu reședință de județ',
            self::COMPONENTA_RESEDINTA_MUNICIPIU => 'Reședință municipiu',
            self::MUNICIPIU => 'Municipiu',
            self::COMPONENTA_MUNICIPIU => 'Componentă municipiu',
            self::ORAS => 'Oraș',
            self::COMPONENTA_ORAS => 'Componentă Oraș',
            self::COMPONENTA_RESEDINTA_ORAS => 'Componentă reședință Oraș',
            self::SAT_APARTINATOR_ORAS => 'Sat aparținător Oraș',
            self::SECTOR => 'Sector',
            self::ORAS_RESEDINTA => 'Oraș reședință de județ',
            self::COMUNA => 'Comună',
            self::SAT_RESEDINTA_COMUNA => 'Sat reședință de comună',
            self::SAT => 'Sat',
            self::SAT_APARTINATOR_MUNICIPIU => 'Sat aparținător de oraș',
            default => 'Localitate'
        };
    }

    public function group(): string
    {
        return match ($this) {

            self::COMPONENTA_RESEDINTA_MUNICIPIU,
            self::COMPONENTA_MUNICIPIU,
            self::SAT_APARTINATOR_MUNICIPIU,
            self::COMPONENTA_RESEDINTA_ORAS,
            self::COMPONENTA_ORAS,
            self::SAT_APARTINATOR_ORAS
            => 'localitati',

                // sate
            self::SAT_RESEDINTA_COMUNA,
            self::SAT
            => 'sate',

                // București
            self::SECTOR
            => 'sectoroare',

            // =========================
            // FALLBACK (nu ar trebui să apară)
            // =========================
            default => 'Altele',
        };
    }


    public function sortOrder(): int
    {
        return match ($this) {
            self::MUNICIPIU_RESEDINTA => 1,
            self::MUNICIPIU => 2,
            self::ORAS => 3,
            self::ORAS_RESEDINTA => 4,
            self::COMUNA => 5,
            self::SAT_RESEDINTA_COMUNA => 6,
            self::SAT => 7,
            self::SECTOR => 8,
            default => 99,
        };
    }

    public static function orderList(): array
    {
        return [
            self::MUNICIPIU_RESEDINTA->value,
            self::MUNICIPIU->value,
            self::ORAS->value,
            self::ORAS_RESEDINTA->value,
            self::COMUNA->value,
            self::SAT_RESEDINTA_COMUNA->value,
            self::SAT->value,
            self::SECTOR->value,
        ];
    }


}
