<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Fixtures;

use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ConstraintViolationBuilder implements ConstraintViolationBuilderInterface
{
    public function __construct(private string $message, private array $parameters, private ExecutionContext $context)
    {
    }

    public function atPath(string $path): static
    {
    }

    public function setParameter(string $key, string $value): static
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function setParameters(array $parameters): static
    {
    }

    public function setTranslationDomain(string $translationDomain): static
    {
    }

    public function setInvalidValue($invalidValue): static
    {
    }

    public function setPlural(int $number): static
    {
    }

    public function setCode(?string $code): static
    {
    }

    public function setCause($cause): static
    {
    }

    public function addViolation(): void
    {
        $this->context->addViolation($this->message, $this->parameters);
    }
}
