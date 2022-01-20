<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Integration;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Yivoff\NifCheck\Test\Fixtures\FakeUser;
use Yivoff\NifCheck\YivoffNifCheckerBundle;

/**
 * @coversNothing
 *
 * @internal
 */
class AttributedClassValidationTest extends KernelTestCase
{
    public function testValidateGoodNif(): void
    {
        $validator = $this->getValidator();

        $goodNifUser = new FakeUser('50502772Q');
        $errors      = $validator->validate($goodNifUser);

        $this->assertCount(0, $errors);
    }

    public function testValidateBadNif(): void
    {
        $validator = $this->getValidator();

        $goodNifUser = new FakeUser('50502772I');
        $errors      = $validator->validate($goodNifUser);

        $this->assertCount(1, $errors);
    }

    protected static function createKernel(array $options = []): TestKernel
    {
        /**
         * @var TestKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->addTestBundle(YivoffNifCheckerBundle::class);

        $kernel->handleOptions($options);

        return $kernel;
    }

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    private function getValidator(): ValidatorInterface
    {
        self::bootKernel([
            'config' => static function (TestKernel $kernel): void {
                $kernel->addTestConfig(__DIR__.'/../Fixtures/config/framework.yaml');
            },
        ]);

        $container =  self::getContainer();

        /** @var ValidatorInterface */
        $validator = $container->get('test_validator');

        return $validator;
    }
}
