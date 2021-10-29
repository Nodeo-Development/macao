<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Definition;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use LogicException;
use Macao\DI\Attributes\Named;
use Macao\DI\Exceptions\CircularDependencyException;
use Macao\DI\Exceptions\DependencyNotFoundException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class ClassDefinition extends AbstractDefinition
{
    private string $implementation;

    public function __construct(string $implementation)
    {
        $this->implementation = $implementation;
    }

    /**
     * @param array $options
     * @inheritDoc
     */
    public function resolve(
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options,
        array $breadcrumb = []
    ): mixed {
        if (in_array($this, $breadcrumb)) {
            $breadcrumb[] = $this;

            throw new CircularDependencyException($breadcrumb);
        }

        $reflectionClass = new ReflectionClass($this->implementation);

        if (!$reflectionClass->isInstantiable()) {
            throw new LogicException('%s is not instantiable');
        }

        $constructor = $reflectionClass->getConstructor();
        $parameters = [];

        if ($constructor) {
            foreach ($constructor->getParameters() as $reflectionParameter) {
                if ($reflectionParameter->hasType()) {
                    // Type resolution
                    $reflectionType = $reflectionParameter->getType();

                    if ($reflectionType instanceof ReflectionUnionType) {
                        throw new LogicException(
                            'The parameter type cannot be a union'
                        );
                    }

                    if ($reflectionType->isBuiltin()) {
                        $parameters[] = $this->resolveParameterByName(
                            $reflectionParameter,
                            $bindings,
                            $options
                        );
                    } else {
                        $parameters[] = $this->resolveParameterByAutoWiring(
                            $reflectionParameter->getType(),
                            $bindings,
                            $options,
                            $breadcrumb
                        );
                    }
                } else {
                    // Name resolution
                    $parameters[] = $this->resolveParameterByName(
                        $reflectionParameter,
                        $bindings,
                        $options
                    );
                }
            }
        }

        return $reflectionClass->newInstance(...$parameters);
    }

    /**
     * @throws Exception
     */
    private function resolveParameterByAutoWiring(
        ReflectionNamedType $reflectionType,
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options,
        array $breadcrumb
    ) {
        $typeName = $reflectionType->getName();

        if (array_key_exists($typeName, $bindings)) {
            return $bindings[$typeName]->resolve($bindings, $options);
        }

        if (!$options['autoWiring']) {
            throw new LogicException(
                'Auto wiring is disabled. ' .
                'Turn on it to resolve the dependencies dynamically'
            );
        }

        $parameterDefinition = new ClassDefinition($typeName);

        $breadcrumb[] = $this;

        return $parameterDefinition->resolve(
            $bindings,
            $options,
            $breadcrumb
        );
    }

    private function resolveParameterByName(
        ReflectionParameter $parameter,
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options
    ) {
        $name = $parameter->getName();

        if (!array_key_exists($name, $bindings)) {
            // Named attribute
            $parameterAttributes = $parameter->getAttributes(Named::class);

            if (empty($parameterAttributes)) {
                throw new LogicException(
                    sprintf(
                        '%s was not bound and no Named attribute is declared to it',
                        $name
                    )
                );
            }

            $namedAttribute = $parameterAttributes[0]->newInstance();
            $name = $namedAttribute->key;

            if (!array_key_exists($name, $bindings)) {
                throw new DependencyNotFoundException($name);
            }
        }

        return $bindings[$name]->resolve(
            $bindings,
            $options
        );
    }

    /**
     * @return string
     */
    public function getImplementation(): string
    {
        return $this->implementation;
    }
}
