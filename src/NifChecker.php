<?php

declare(strict_types=1);

namespace Yivoff\NifCheck;

use function abs;
use function array_search;
use function ltrim;
use function strlen;
use function strtoupper;
use function strtr;
use function substr;
use function trim;

class NifChecker
{
    public const CONTROL
        = [
            'T',
            'R',
            'W',
            'A',
            'G',
            'M',
            'Y',
            'F',
            'P',
            'D',
            'X',
            'B',
            'N',
            'J',
            'Z',
            'S',
            'Q',
            'V',
            'H',
            'L',
            'C',
            'K',
            'E',
        ];

    public function verify(string $nif): bool
    {
        $nif = trim(strtoupper($nif));

        if (strlen($nif) > 9 || strlen($nif) < 8) {
            return false;
        }

        // We do not test for K-L-M starting documents.
        if (preg_match('/^[0-9]{7,8}[TRWAGMYFPDXBNJZSQVHLCKE]/', $nif)) {
            // DNI
            return $this->verifyDni($nif);
        }

        if (preg_match('/^[XYZ][0-9]{7,8}[TRWAGMYFPDXBNJZSQVHLCKE]/', $nif)) {
            // NIE
            return $this->verifyNie($nif);
        }

        if (preg_match('/^[ABCDEFGHJNPQRSUVW][0-9]{7,8}[JABCDEFGHI]?/', $nif)) {
            // CIF
            return $this->verifyCif($nif);
        }

        return false;
    }

    private function verifyDni(string $dni): bool
    {
        $dni     = ltrim($dni, 'KLM');
        $number  = (int) substr($dni, 0, strlen($dni) - 1);
        $control = substr($dni, -1, 1);

        return self::CONTROL[abs($number % 23)] === $control;
    }

    private function verifyNie(string $nie): bool
    {
        $dni = strtr($nie, 'XYZ', '012');

        return $this->verifyDni($dni);
    }

    /**
     * @see https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
     */
    private function verifyCif(string $cif): bool
    {
        $letterControl = ['J', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        $providedControl = substr($cif, -1, 1);
        $number          = substr($cif, 1, 7);

        // Sumar los dígitos de las posiciones pares. Suma = A
        $sumA = (int) $number[1] + (int) $number[3] + (int) $number[5];

        // Para cada uno de los dígitos de las posiciones impares, multiplicarlo por 2
        // y sumar los dígitos del resultado.
        $sumB = 0;
        foreach ([0, 2, 4, 6] as $digit) {
            $i = (int) $number[$digit] * 2;
            if ($i < 10) {
                $sumB += $i;

                continue;
            }

            $s    = (string) $i;
            $sumB += (int) $s[0] + (int) $s[1];
        }

        // Sumar A + B = C
        $sumC = $sumA + $sumB;
        // Tomar sólo el dígito de las unidades de C. Lo llamaremos dígito E.
        $digitE = (int) substr((string) $sumC, -1, 1);

        // Si el dígito E es distinto de 0 lo restaremos a 10. D = 10-E.
        // Esta resta nos da D. Si no, si el dígito E es 0 entonces D = 0
        $expectedControl = 0 === $digitE ? 0 : 10 - $digitE;

        $numericControl = preg_match('/^[0-9]$/', $providedControl) ?
            (int) $providedControl
            : array_search($providedControl, $letterControl, true);

        return $expectedControl === $numericControl;
    }
}
