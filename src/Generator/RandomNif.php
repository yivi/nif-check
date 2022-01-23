<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Generator;

use Yivoff\NifCheck\CifControlDigit;
use Yivoff\NifCheck\Constants;
use function abs;
use function random_int;
use function str_repeat;
use function str_shuffle;
use function strlen;
use function strtr;
use function substr;

class RandomNif
{
    public function generate(): string
    {
        return match (random_int(1, 3)) {
            1 => $this->generateDni(),
            2 => $this->generateNie(),
            3 => $this->generateCif()
        };
    }

    public function generateDni(): string
    {
        $digits = $this->randomDigits(8);

        return $digits.Constants::VALID_CONTROL_DNI_CHARS[abs((int) $digits % 23)];
    }

    public function generateNie(): string
    {
        $prefix     = Constants::VALID_NIE_PREFIX[random_int(0, strlen(Constants::VALID_NIE_PREFIX) - 1)];
        $digits     = $this->randomDigits(7);
        $partialNie = $prefix.$digits;

        $fullNumber = (int) strtr($partialNie, Constants::VALID_NIE_PREFIX, '012');

        return $partialNie.Constants::VALID_CONTROL_DNI_CHARS[abs($fullNumber % 23)];
    }

    /** @psalm-param ?string 'A' | 'B' | 'C' | 'D' | 'E' | 'F' | 'G' | 'H' | 'J' | 'N' | 'P' | 'Q' | 'R' | 'S' | 'U' | 'V' | 'W' */
    public function generateCif(?string $prefix = null): string
    {
        $prefix ??= Constants::VALID_CIF_PREFIX[random_int(0, strlen(Constants::VALID_CIF_PREFIX) - 1)];
        $digits = $this->randomDigits(7);

        $numericControl = (new CifControlDigit())->calculate($digits);

        if (in_array($prefix, ['N', 'P', 'Q', 'R', 'S', 'W'], true)) {
            $control = substr(Constants::VALID_CIF_CONTROL, $numericControl, 1);
        } else {
            $control = (string) $numericControl;
        }

        return $prefix.$digits.$control;
    }

    private function randomDigits(int $howMany): string
    {
        return substr(str_shuffle(str_repeat('0123456789', $howMany)), 0, $howMany);
    }
}
