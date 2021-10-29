<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\DI;

interface Container
{
    /**
     * Returns the value associated to the specified key.
     *
     * @template T The value type.
     * @param class-string<T> $key The value key.
     * @return T The value associated to the specified key.
     */
    public function get(string $key);

    /**
     * Checks whether the specified key was bound.
     *
     * @param string $key The key.
     * @return bool <code>true</code> if the specified key was bound,
     * <code>false</code> otherwise.
     */
    public function has(string $key): bool;
}
