<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class ValidNif extends Constraint
{
    public string $message = "The string '{{ nif }}' is not a valid Spanish NIF";
}
