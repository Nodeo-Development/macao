<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Definition;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class SingletonClassDefinition extends ClassDefinition
{
    private ?object $instance;

    #[Pure]
    public function __construct(string $implementation)
    {
        parent::__construct($implementation);

        $this->instance = null;
    }

    public function resolve(
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options,
        array $breadcrumb = []
    ): mixed {
        if (is_null($this->instance)) {
            $this->instance = parent::resolve($bindings, $options);
        }

        return $this->instance;
    }
}
