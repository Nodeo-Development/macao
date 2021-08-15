<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Container;

use JetBrains\PhpStorm\Pure;
use Macao\DI\Definition\Definition;
use Macao\DI\Exceptions\NotFoundException;
use Macao\DI\Resolver\Resolver;

class ContainerImpl implements Container
{
    private array $bindings;
    private array $options;
    private Resolver $resolver;

    #[Pure]
    public function __construct(array $bindings, array $options = [])
    {
        // Put the container in the binds array
        $bindings[Container::class] = new Definition($this);

        $this->bindings = $bindings;
        $this->options = $options;
        $this->resolver = new Resolver($bindings);
    }

    public function get(string $key)
    {
        if ($this->has($key)) {
            return $this->resolver->resolve($key);
        } elseif ($this->options['autoWiring']) {
            return $this->resolver->resolve($key);
        } else {
            throw new NotFoundException($key);
        }
    }

    private function has(string $key): bool
    {
        return array_key_exists($key, $this->bindings);
    }
}
