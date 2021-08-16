<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Macao\DI\Definition\AbstractDefinition;
use Macao\DI\Definition\ClassDefinition;
use Macao\DI\Definition\InstanceDefinition;
use Macao\DI\Exceptions\DependencyNotFoundException;
use Macao\DI\Exceptions\UnresolvableDependencyException;

/**
 * Class ContainerImpl
 *
 * The default {@link Container} implementation.
 */
class ContainerImpl implements Container
{
    /**
     * The container bindings.
     *
     * @var AbstractDefinition[]
     */
    private array $bindings;

    /**
     * The container options.
     *
     * @var array
     */
    #[ArrayShape([
        'autoWiring' => 'bool'
    ])]
    private array $options;

    /**
     * ContainerImpl constructor.
     *
     * @param AbstractDefinition[] $bindings The container bindings.
     * @param array $options The container options.
     */
    #[Pure]
    public function __construct(
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options
    ) {
        // Binds the container
        $bindings[Container::class] = new InstanceDefinition($this);

        $this->bindings = $bindings;
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        if ($this->has($key)) {
            $definition = $this->bindings[$key];
        } elseif ($this->options['autoWiring']) {
            $definition = new ClassDefinition($key);
        } else {
            throw new DependencyNotFoundException($key);
        }

        try {
            return $definition->resolve($this->bindings, $this->options);
        } catch (Exception $e) {
            throw new UnresolvableDependencyException($key, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->bindings);
    }
}
