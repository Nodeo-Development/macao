<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Definition;

use JetBrains\PhpStorm\ArrayShape;

class ScalarDefinition extends AbstractDefinition
{
    private mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function resolve(
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options,
        array $breadcrumb = []
    ): mixed {
        return $this->value;
    }
}
