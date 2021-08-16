<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Exceptions;

use Macao\DI\Definition\ClassDefinition;
use RuntimeException;

class CircularDependencyException extends RuntimeException
{
    /**
     * @param ClassDefinition[] $breadcrumb
     */
    public function __construct(array $breadcrumb)
    {
        parent::__construct(
            sprintf(
                'Circular dependency detected : %s',
                join(
                    ' > ',
                    array_map(function ($class) {
                        return $class->getImplementation();
                    }, $breadcrumb)
                )
            )
        );
    }
}
