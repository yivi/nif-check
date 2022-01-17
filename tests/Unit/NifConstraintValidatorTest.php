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
use Yivoff\NifCheck\Validator\NifConstraint;
use Yivoff\NifCheck\Validator\NifConstraintValidator;

/**
 * @covers \Yivoff\NifCheck\Validator\NifConstraintValidator
 *
 * @internal
 */
class NifConstraintValidatorTest extends TestCase
{
    public function testInvalidConstraint(): void
    {
        $someConstraint = new NotBlank();
        $validator      = new NifConstraintValidator(new NifChecker());
        $fakeNif        = 'whatever';

        $this->expectException(UnexpectedTypeException::class);
        $validator->validate($fakeNif, $someConstraint);
    }

    public function testIgnoresBlanksOrNulls(): void
    {
        $validator     = $this->createValidator(true);
        $validator->validate(null, $this->createMock(NifConstraint::class));
    }

    public function testValidationOnlyWithStrings(): void
    {
        $nifConstraint = $this->createMock(NifConstraint::class);
        $validator     = $this->createValidator(true);

        $this->expectException(UnexpectedValueException::class);
        $validator->validate(true, $nifConstraint);

        $this->expectException(UnexpectedValueException::class);
        $validator->validate(9091928, $nifConstraint);
    }

    public function testInvalidStringBuildsViolation(): void
    {
        $validator = $this->createValidator(false);
        $validator->validate('INVALID_NIF', $this->createMock(NifConstraint::class));
    }

    public function testValidNifDoesNotBuildViolation(): void
    {
        $validator = $this->createValidator(true);
        $validator->validate('VALID_NIF', $this->createMock(NifConstraint::class));
    }

    private function createValidator(bool $validationSucceeds): ConstraintValidator
    {
        $executionContextMock = $this->createMock(ExecutionContextInterface::class);
        $executionContextMock
            ->expects($validationSucceeds ? $this->never() : $this->once())->method('buildViolation');

        $checkerMock = $this->createMock(NifChecker::class);
        $checkerMock->method('verify')->willReturn($validationSucceeds);

        $this->assertEquals($validationSucceeds, $checkerMock->verify('abc'));

        $validator = new NifConstraintValidator($checkerMock);
        $validator->initialize($executionContextMock);

        return $validator;
    }
}
