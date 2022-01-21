<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Fixtures;

use Yivoff\NifCheck\Validator\ValidNif;

class FakeUserEmptyNif
{
    public function __construct(
        #[ValidNif]
        public string $nif
    ) {
    }
}
