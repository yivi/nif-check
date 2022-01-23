<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Unit;

use PHPUnit\Framework\TestCase;
use Yivoff\NifCheck\Generator\RandomNif;
use Yivoff\NifCheck\NifChecker;

/**
 * @covers  \Yivoff\NifCheck\Generator\RandomNif
 * @covers \Yivoff\NifCheck\CifControlDigit
 * @covers \Yivoff\NifCheck\NifChecker
 *
 * @internal
 */
class RandomNifTest extends TestCase
{
    public function testCif(): void
    {
        $checker = new NifChecker();
        $random  = new RandomNif();
        for ($i = 0; $i < 200; ++$i) {
            $generatedCif = $random->generateCif();
            $this->assertTrue($checker->verify($generatedCif), "{$generatedCif} does not validate");
        }
    }

    public function testDni(): void
    {
        $checker = new NifChecker();
        $random  = new RandomNif();
        for ($i = 0; $i < 200; ++$i) {
            $generatedDni = $random->generateDni();
            $this->assertTrue($checker->verify($generatedDni), "{$generatedDni} does not validate");
        }
    }

    public function testNie(): void
    {
        $checker = new NifChecker();
        $random  = new RandomNif();
        for ($i = 0; $i < 200; ++$i) {
            $generatedNie = $random->generateNie();
            $this->assertTrue($checker->verify($generatedNie), "{$generatedNie} does not validate");
        }
    }

    public function testGenerate(): void
    {
        $checker = new NifChecker();
        $random  = new RandomNif();
        for ($i = 0; $i < 15; ++$i) {
            $generatedNif = $random->generate();
            $this->assertTrue($checker->verify($generatedNif), "{$generatedNif} does not validate");
        }
    }
}
