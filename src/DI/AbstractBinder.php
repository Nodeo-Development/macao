<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI;

use InvalidArgumentException;
use JetBrains\PhpStorm\ExpectedValues;
use Macao\DI\Definition\ClassDefinition;
use Macao\DI\Definition\InstanceDefinition;
use Macao\DI\Definition\ScalarDefinition;
use Macao\DI\Definition\SingletonClassDefinition;

abstract class AbstractBinder
{
    private array $bindings = [];

    abstract public function configure(): void;

    public function getBindings(): array
    {
        return $this->bindings;
    }

    protected function bind(
        string $key,
        string $implementation,
        #[ExpectedValues(
            valuesFromClass: Scope::class
        )] int $scope = Scope::DEFAULT
    ): void {
        if (key_exists($key, $this->bindings)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is already bound to %s',
                    $key,
                    $this->bindings[$key]
                )
            );
        }

        if (!class_exists($key) && !interface_exists($key)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be a valid class/interface',
                    $key
                )
            );
        }

        if (!in_array($scope, [Scope::DEFAULT, Scope::SINGLETON])) {
            throw new InvalidArgumentException(
                'Invalid scope.' .
                'It must be either Scope::DEFAULT or Scope::SINGLETON'
            );
        }

        if ($scope == Scope::SINGLETON) {
            $definition = new SingletonClassDefinition($implementation);
        } else {
            $definition = new ClassDefinition($implementation);
        }

        $this->bindings[$key] = $definition;
    }

    protected function bindInstance(
        string $definition,
        object $instance
    ): void {
        if (!class_exists($definition) && !interface_exists($definition)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be a valid class/interface',
                    $definition
                )
            );
        }

        if (!($instance instanceof $definition)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an instance of %s',
                    $instance,
                    $definition
                )
            );
        }

        $this->bindings[$definition] = new InstanceDefinition($instance);
    }

    protected function bindScalar(
        string $definition,
        mixed $value
    ): void {
        if (key_exists($definition, $this->bindings)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s is already bound to %s',
                    $definition,
                    $this->bindings[$definition]
                )
            );
        }

        if (!is_scalar($value)) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be a scalar',
                    $value
                )
            );
        }

        $this->bindings[$definition] = new ScalarDefinition($value);
    }
}
