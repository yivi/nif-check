<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Fixtures;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\MetadataInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class ExecutionContext implements ExecutionContextInterface
{
    public array $fakeViolations = [];

    public function addViolation(string $message, array $params = []): void
    {
        $this->fakeViolations[] = ['message' => $message, 'params' => $params];
    }

    public function buildViolation(string $message, array $parameters = []): ConstraintViolationBuilderInterface
    {
        return new ConstraintViolationBuilder($message, $parameters, $this);
    }

    public function getValidator(): ValidatorInterface
    {
    }

    public function getObject(): ?object
    {
        return null;
    }

    public function setNode($value, ?object $object, MetadataInterface $metadata = null, string $propertyPath): void
    {
    }

    public function setGroup(?string $group): void
    {
    }

    public function setConstraint(Constraint $constraint): void
    {
    }

    public function markGroupAsValidated(string $cacheKey, string $groupHash): void
    {
    }

    public function isGroupValidated(string $cacheKey, string $groupHash): bool
    {
        return true;
    }

    public function markConstraintAsValidated(string $cacheKey, string $constraintHash): void
    {
    }

    public function isConstraintValidated(string $cacheKey, string $constraintHash): bool
    {
        return true;
    }

    public function markObjectAsInitialized(string $cacheKey): void
    {
    }

    public function isObjectInitialized(string $cacheKey): bool
    {
        return true;
    }

    public function getViolations(): ConstraintViolationListInterface
    {
    }

    public function getRoot(): mixed
    {
        return '';
    }

    public function getValue(): mixed
    {
        return '';
    }

    public function getMetadata(): ?MetadataInterface
    {
    }

    public function getGroup(): ?string
    {
        return '';
    }

    public function getClassName(): ?string
    {
        return '';
    }

    public function getPropertyName(): ?string
    {
        return '';
    }

    public function getPropertyPath(string $subPath = ''): string
    {
        return $subPath;
    }
}
