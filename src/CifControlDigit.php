<?php

declare(strict_types=1);

namespace Yivoff\NifCheck;

class CifControlDigit
{
    public function calculate(string $number): int
    {
        // Suma A. Sumar los dígitos de las posiciones pares.
        $sumA = (int) $number[1] + (int) $number[3] + (int) $number[5];

        // Suma B.
        // Para cada uno de los dígitos de las posiciones impares, multiplicarlo por 2
        // y sumar los dígitos del resultado.
        $sumB = 0;
        foreach ([0, 2, 4, 6] as $digit) {
            $i = (int) $number[$digit] * 2;

            $sumB += ($i < 10) ? $i : ($i % 10) + 1;
        }

        // Sumar A + B = C
        $sumC = $sumA + $sumB;

        // Tomar solo el dígito de las unidades de C. Lo llamaremos dígito E.
        $digitE = $sumC % 10;

        // Si el dígito E es distinto de 0 lo restaremos a 10. D = 10-E.
        // Esta resta nos da D. Si no, si el dígito E es 0 entonces D = 0
        return 0 === $digitE ? 0 : 10 - $digitE;
    }
}
