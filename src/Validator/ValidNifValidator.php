<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Yivoff\NifCheck\NifChecker;
use function is_string;

class ValidNifValidator extends ConstraintValidator
{
    public function __construct(private NifChecker $checker)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidNif) {
            throw new UnexpectedTypeException($constraint, ValidNif::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($this->checker->verify($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ nif }}', $value)
            ->addViolation()
        ;
    }
}
