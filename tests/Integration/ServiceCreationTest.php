<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\Test\Integration;

use Nyholm\BundleTest\TestKernel;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Yivoff\NifCheck\Validator\NifConstraintValidator;
use Yivoff\NifCheck\YivoffNifCheckerBundle;

/**
 * @coversNothing
 *
 * @internal
 */
class ServiceCreationTest extends KernelTestCase
{
    public function testServiceCreation(): void
    {
        // Get the container
        self::bootKernel();
        $container = self::getContainer();

        $this->assertTrue($container->has(YivoffNifCheckerBundle::BUNDLE_PREFIX.'.constraint_validator'));
        $this->assertTrue($container->has(NifConstraintValidator::class));

        $this->assertInstanceOf(NifConstraintValidator::class, $container->get(NifConstraintValidator::class));
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
}
