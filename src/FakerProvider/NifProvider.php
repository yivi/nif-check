<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\FakerProvider;

use Faker\Provider\Base;
use Yivoff\NifCheck\Generator\RandomNif;

/**
 * @codeCoverageIgnore
 */
class NifProvider extends Base
{
    /**
     * @param \Faker\Generator $generator
     *
     * @noinspection PhpMissingParentConstructorInspection
     * @noinspection PhpMissingParamTypeInspection
     */
    public function __construct(protected $generator, private RandomNif $nifGenerator, )
    {
    }

    public function spanishNif(): string
    {
        return $this->nifGenerator->generate();
    }

    public function spanishNie(): string
    {
        return $this->nifGenerator->generateNie();
    }

    /** @psalm-param ?string 'A' | 'B' | 'C' | 'D' | 'E' | 'F' | 'G' | 'H' | 'J' | 'N' | 'P' | 'Q' | 'R' | 'S' | 'U' | 'V' | 'W' */
    public function spanishCif(?string $prefixLetter): string
    {
        return $this->nifGenerator->generateCif($prefixLetter);
    }
}
