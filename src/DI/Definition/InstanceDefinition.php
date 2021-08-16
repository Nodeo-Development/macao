<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Definition;

use JetBrains\PhpStorm\ArrayShape;

class InstanceDefinition extends AbstractDefinition
{
    private object $instance;

    public function __construct(object $instance)
    {
        $this->instance = $instance;
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
    ): object {
        return $this->instance;
    }
}
