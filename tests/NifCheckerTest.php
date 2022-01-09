<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test;

use Generator;
use PHPUnit\Framework\TestCase;
use Yivoff\NifCheck\NifChecker;

/**
 * @covers \Yivoff\NifCheck\NifChecker
 *
 * @internal
 */
class NifCheckerTest extends TestCase
{
    /**
     * @dataProvider provideDni
     */
    public function testCheckingDni(string $dni, bool $expectedResult): void
    {
        $checker = new NifChecker();

        $this->assertSame($expectedResult, $checker->verify($dni));
    }

    /**
     * @dataProvider provideNie
     */
    public function testCheckingNie(string $nie, bool $expectedResult): void
    {
        $checker = new NifChecker();

        $this->assertSame($expectedResult, $checker->verify($nie));
    }

    /**
     * @dataProvider provideCif
     */
    public function testCheckingCif(string $cif, bool $expectedResult): void
    {
        $checker = new NifChecker();

        $this->assertSame($expectedResult, $checker->verify($cif));
    }

    private function provideDni(): Generator
    {
        $dni_collection = [
            ['50502772Q', true],
            ['02914480N', true],
            ['10619201D', true],
            ['09275237G', true],
            ['25688628C', true],
            ['68554072L', true],
            ['82468293E', true],
            ['86834928S', true],
            ['', false],
            ['02914481N', false],
            ['2914481N', false],
            ['050502772Q', false],
            ['K0000000D', false],
        ];

        foreach ($dni_collection as $dni_item) {
            yield $dni_item;
        }
    }

    private function provideNie(): Generator
    {
        $nie_collection = [
            ['X6499149Q', true],
            ['Z7041887M', true],
            ['Z5118021C', true],
            ['X9740873M', true],
            ['Y2607139B', true],
            ['Y7642308Z', false],
            ['Y7642308X', false],
        ];

        foreach ($nie_collection as $item) {
            yield $item;
        }
    }

    private function provideCif(): Generator
    {
        $nie_collection = [
            ['J65869380', true],
            // 6 5 8 6 9 3 8 ' => 0 ],
            // 5 + 6 + 3 = 14
            // 12 + 16 + 18 + 16
            ['Q5105232B', true],
            ['S0292030D', true],
            ['S7536373I', true],
            ['F06203020', true],
            ['P9786555D', true],
            ['W4870853A', true],
            ['J97245401', true],
            ['A06987630', true],
            ['A0698763J', true],
            ['A06987640', false],
            ['P97865593', false],
        ];

        foreach ($nie_collection as $item) {
            yield $item;
        }
    }
}
