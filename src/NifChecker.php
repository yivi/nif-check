<?php

declare(strict_types=1);

namespace Yivoff\NifCheck;

use function abs;
use function in_array;
use function strlen;
use function strpos;
use function strtoupper;
use function strtr;
use function substr;
use function trim;

class NifChecker
{
    public function verify(string $nif): bool
    {
        $nif = trim(strtoupper($nif));

        if (strlen($nif) > 9 || strlen($nif) < 8) {
            return false;
        }

        // We do not test for K-L-M starting documents.
        return match (1) {
            // DNI
            preg_match('/^\d{7,8}['.Constants::VALID_CONTROL_DNI_CHARS.']/', $nif) => $this->verifyDni($nif),
            // NIE
            preg_match('/^['.Constants::VALID_NIE_PREFIX.']\d{7}['.Constants::VALID_CONTROL_DNI_CHARS.']/', $nif) => $this->verifyNie($nif),
            // CIF
            preg_match('/^['.Constants::VALID_CIF_PREFIX.']\d{7}['.Constants::VALID_CIF_CONTROL.']?/', $nif) => $this->verifyCif($nif),
            // unmatched and wrong
            default => false
        };
    }

    private function verifyDni(string $dni): bool
    {
        $number  = (int) substr($dni, 0, strlen($dni) - 1);
        $control = substr($dni, -1, 1);

        return Constants::VALID_CONTROL_DNI_CHARS[abs($number % 23)] === $control;
    }

    private function verifyNie(string $nie): bool
    {
        $dni = strtr(substr($nie, 0, 8), Constants::VALID_NIE_PREFIX, '012').substr($nie, -1, 1);

        return $this->verifyDni($dni);
    }

    /**
     * @see https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
     * @see http://www.jagar.es/economia/ccif.htm
     */
    private function verifyCif(string $cif): bool
    {
        $providedControl = substr($cif, -1, 1);
        $number          = substr($cif, 1, 7);
        $letter          = substr($cif, 0, 1);

        $calculatedControl = (new CifControlDigit())->calculate($number);

        // Si el dígito de control es numérico, ya es el valor esperado.
        // Si es una letra, buscarla en los caracteres válidos, y su posición es el valor.
        if (in_array($letter, ['N', 'P', 'Q', 'R', 'S', 'W'], true)) {
            $numericControl = strpos(Constants::VALID_CIF_CONTROL, $providedControl);
        } else {
            $numericControl = (int) $providedControl;
        }

        return $calculatedControl === $numericControl;
    }
}
