<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI\Container;

interface Container
{
    /**
     * Returns the object associated to the specified key.
     *
     * @template T The object type.
     * @param class-string<T> $key The object key.
     * @return T The object associated to the specified key.
     */
    public function get(string $key);
}
