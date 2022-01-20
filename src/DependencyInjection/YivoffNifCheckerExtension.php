<?php

declare(strict_types=1);

namespace Yivoff\NifCheck\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Yivoff\NifCheck\NifChecker;
use Yivoff\NifCheck\Validator\ValidNifValidator;
use Yivoff\NifCheck\YivoffNifCheckerBundle;

class YivoffNifCheckerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $container->register(NifChecker::class)
            ->setPublic(false)
        ;

        if (class_exists('Symfony\Component\Validator\Validation')) {
            $container->register(ValidNifValidator::class)
                ->setArgument(0, new Reference(NifChecker::class))
                ->addTag('validator.constraint_validator')
                ->setPublic(false)
        ;

            $container->setAlias(YivoffNifCheckerBundle::BUNDLE_PREFIX.'.constraint_validator', ValidNifValidator::class)
                ->setPublic(true)
        ;
        }
    }
}
