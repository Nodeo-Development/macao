<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Resolver;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use LogicException;
use Macao\DI\Definition\ClassDefinition;
use Macao\DI\Exceptions\NotFoundException;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use RuntimeException;

class Resolver
{
    private array $bindings;
    private array $dependencies;

    #[Pure]
    public function __construct(array $bindings)
    {
        $this->bindings = $bindings;
        $this->dependencies = [];

        foreach ($bindings as $key => $bind) {
            if (!($bind instanceof ClassDefinition)) {
                $value = $bind->getValue();
                $this->dependencies[$key] = $value;
            }
        }
    }

    public function resolve(string $key): mixed
    {
        if (array_key_exists($key, $this->dependencies)) {
            return $this->dependencies[$key];
        } else {
            if (!class_exists($key) && !interface_exists($key)) {
                throw new NotFoundException($key);
            }

            try {
                $implementation = array_key_exists(
                    $key,
                    $this->bindings
                ) ? $this->bindings[$key]->getValue() : $key;
                $class = new ReflectionClass($implementation);

                if (!$class->isInstantiable()) {
                    throw new InvalidArgumentException(
                        sprintf(
                            '"%s" is not instantiable',
                            $key
                        )
                    );
                }

                $constructor = $class->getConstructor();
                $parameters = [];

                if ($constructor) {
                    foreach ($constructor->getParameters() as $parameter) {
                        $parameterType = $parameter->getType();

                        if (!$parameterType && !($parameterType instanceof ReflectionNamedType)) {
                            throw new LogicException(
                                sprintf(
                                    'Cannot instantiate %s because %s is not typed',
                                    $key,
                                    $parameter->getName()
                                )
                            );
                        }

                        $parameters[] = $this->resolve(
                            $parameterType->getName()
                        );
                    }
                }

                $instance = $class->newInstance(...$parameters);

                if (array_key_exists($key, $this->bindings)) {
                    $definition = $this->bindings[$key];

                    if (
                        $definition instanceof ClassDefinition &&
                        $definition->getStatus() === ClassDefinition::SINGLETON
                    ) {
                        // Put the instance on the dependencies array if the definition is marked as a singleton
                        $this->dependencies[$key] = $instance;
                    }
                }

                return $instance;
            } catch (ReflectionException $exception) {
                throw new RuntimeException($exception->getMessage());
            }
        }
    }
}
