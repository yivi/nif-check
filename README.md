# NIF (DNI/NIE/CIF) checker & generator

[![Latest Stable Version](http://poser.pugx.org/yivoff/nif-spain/v)](https://packagist.org/packages/yivoff/nif-spain)
[![Total Downloads](http://poser.pugx.org/yivoff/nif-spain/downloads)](https://packagist.org/packages/yivoff/nif-spain)
[![Latest Unstable Version](http://poser.pugx.org/yivoff/nif-spain/v/unstable)](https://packagist.org/packages/yivoff/nif-spain)
[![License](http://poser.pugx.org/yivoff/nif-spain/license)](https://packagist.org/packages/yivoff/nif-spain)
![Tests](https://github.com/yivi/CommonMarkBundle/actions/workflows/bundle_tests.yaml/badge.svg)

Simple library to check that fiscal identifiers are valid according to Spanish legislation, and to generate valid fiscal
identifiers.

## Installation

```
composer require yivoff/nif-check
```

### Usage

```php
$checker = new \Yivoff\NifCheck\NifChecker();

// returns true if valid
$checker->verify($anyNif);
```
### NIF Generator

An utility is provided to generate valid NIFs, which could be useful for testing purposes or similar scenarios.

```php

$generator = new \Yivoff\NifCheck\Generator\RandomNif();

// Generates a valid DNI
$validDni = $generator->generateDni();
 
// Generates a valid NIE
$validNie = $generator->generateNie();
 
// Generates a valid CIF
$validCif = $generator->generateCif();
 
// Generates a valid NIF (randomly DNI, NIF, or CIF)
$validNif = $generator->generate();
```

### Symfony Validator integration

The package provides a Symfony Validator attribute, which can simply be used like this:

```php
use Yivoff\NifCheck\Validator\ValidNif;

class User
{
    #[ValidNif]
    public string $nif;
}
```
### fakerphp/faker Integration

The package also includes a Faker Provider.

```php
$faker = Faker\Factory::create();
$faker->addProvider(new \Yivoff\NifCheck\FakerProvider\NifProvider($faker, new \Yivoff\NifCheck\Generator\RandomNif()));

// now you can fake NIFs

$faker->spanishDni();
$faker->spanishNie();
$faker->spanishCif();
$faker->spanishNif();
```

----
See:
1. https://es.wikipedia.org/wiki/N%C3%BAmero_de_identificaci%C3%B3n_fiscal
2. https://es.wikipedia.org/wiki/C%C3%B3digo_de_identificaci%C3%B3n_fiscal
