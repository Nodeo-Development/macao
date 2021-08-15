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
use Macao\DI\Definition\Definition;

abstract class AbstractBinder
{
    private array $bindings = [];

    abstract public function configure(): void;

    public function getBindings(): array
    {
        return $this->bindings;
    }

    protected function bindScalar(string $definition, mixed $value): void
    {
        if (key_exists($definition, $this->bindings)) {
            throw new InvalidArgumentException(
                sprintf(
                    "%s has already be bound to %s",
                    $definition,
                    $this->bindings[$definition]
                )
            );
        }

        $this->bindings[$definition] = new Definition($value);
    }

    /**
     * @template T
     * @param class-string<T> $definition
     * @param T $instance
     */
    protected function bindInstance(
        string $definition,
        object $instance
    ): void {
        if (!class_exists($definition) && !interface_exists($definition)) {
            throw new InvalidArgumentException(
                sprintf("%s must exist", $definition)
            );
        }

        if (key_exists($definition, $this->bindings)) {
            throw new InvalidArgumentException(
                sprintf(
                    "%s has already be bound to %s",
                    $definition,
                    $this->bindings[$definition]
                )
            );
        }

        $this->bindings[$definition] = new Definition($instance);
    }

    protected function bind(
        string $definition,
        string $implementation,
        #[ExpectedValues([
            ClassDefinition::RE_INSTANTIABLE,
            ClassDefinition::SINGLETON
        ])] int $status = ClassDefinition::RE_INSTANTIABLE
    ) {
        if (
            !(class_exists($definition) && interface_exists(
                $definition
            )) && !class_exists($implementation)
        ) {
            throw new InvalidArgumentException(
                sprintf("%s and %s must exist", $definition, $implementation)
            );
        }

        if (key_exists($definition, $this->bindings)) {
            throw new InvalidArgumentException(
                sprintf(
                    "%s has already be bound to %s",
                    $definition,
                    $this->bindings[$definition]
                )
            );
        }

        $this->bindings[$definition] = new ClassDefinition(
            $implementation,
            $status
        );
    }
}
