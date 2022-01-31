<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Unit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use Yivoff\NifCheck\NifChecker;
use Yivoff\NifCheck\Test\Fixtures\ExecutionContext;
use Yivoff\NifCheck\Validator\ValidNif;
use Yivoff\NifCheck\Validator\ValidNifValidator;

/**
 * @covers \Yivoff\NifCheck\Validator\ValidNifValidator
 *
 * @internal
 */
class ValidNifValidatorTest extends TestCase
{
    public function testInvalidConstraint(): void
    {
        $someConstraint = new NotBlank();
        $validator      = new ValidNifValidator(new NifChecker());
        $fakeNif        = 'whatever';

        $this->expectException(UnexpectedTypeException::class);
        $validator->validate($fakeNif, $someConstraint);
    }

    public function testIgnoresBlanksOrNulls(): void
    {
        $executionContext = new ExecutionContext();
        $validator        = $this->createFailingValidator($executionContext);

        $validator->validate(null, $this->createMock(ValidNif::class));
        $this->assertEmpty($executionContext->fakeViolations);

        $validator->validate('', $this->createMock(ValidNif::class));
        $this->assertEmpty($executionContext->fakeViolations);
    }

    public function testValidationOnlyWithStrings(): void
    {
        $executionContext = new ExecutionContext();
        $nifConstraint    = $this->createMock(ValidNif::class);
        $validator        = $this->createPassingValidator($executionContext);

        $this->expectException(UnexpectedValueException::class);
        $validator->validate(true, $nifConstraint);

        $this->expectException(UnexpectedValueException::class);
        $validator->validate(9091928, $nifConstraint);
    }

    public function testInvalidStringBuildsViolation(): void
    {
        $executionContext = new ExecutionContext();
        $validator        = $this->createFailingValidator($executionContext);
        $validator->validate('INVALID_NIF', $this->createMock(ValidNif::class));

        $this->assertCount(1, $executionContext->fakeViolations);
    }

    public function testValidNifDoesNotBuildViolation(): void
    {
        $executionContext = new ExecutionContext();
        $validator        = $this->createPassingValidator($executionContext);
        $validator->validate('VALID_NIF', $this->createMock(ValidNif::class));

        $this->assertEmpty($executionContext->fakeViolations);
    }

    private function createPassingValidator(ExecutionContextInterface $context): ConstraintValidator
    {
        $checkerMock = $this->createMock(NifChecker::class);
        $checkerMock->method('verify')->willReturn(true);

        $validator = new ValidNifValidator($checkerMock);
        $validator->initialize($context);

        return $validator;
    }

    private function createFailingValidator(ExecutionContextInterface $context): ConstraintValidator
    {
        $checkerMock = $this->createMock(NifChecker::class);
        $checkerMock->method('verify')->willReturn(false);

        $validator = new ValidNifValidator($checkerMock);
        $validator->initialize($context);

        return $validator;
    }
}
