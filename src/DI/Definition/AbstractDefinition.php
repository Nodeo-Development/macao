<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Definition;

use Exception;
use JetBrains\PhpStorm\ArrayShape;

abstract class AbstractDefinition
{
    /**
     * Resolves the definition in the specified context.
     *
     * @param AbstractDefinition[] $bindings The bound definitions.
     * @param array $options The options.
     * @param array $breadcrumb The dependencies breadcrumb.
     * @return mixed The resolved definition value.
     * @throws Exception If an error occurred during the definition resolution.
     */
    abstract public function resolve(
        array $bindings,
        #[ArrayShape([
            'autoWiring' => 'bool'
        ])] array $options,
        array $breadcrumb = []
    ): mixed;
}
