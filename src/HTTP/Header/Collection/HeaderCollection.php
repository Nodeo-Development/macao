<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Header\Collection;

use Iterator;
use Macao\HTTP\Header\Header;

/**
 * Interface HeaderCollection
 *
 * TODO
 */
interface HeaderCollection extends Iterator
{
    /**
     * Checks if the collection contains the specified header.
     *
     * @param string $name The header name.
     * @return bool <code>true</code> if the collection contains the
     * specified header, <code>false</code> otherwise.
     */
    public function hasHeader(string $name): bool;

    /**
     * Returns the header with the specified name.
     *
     * @param string $name The header name.
     * @return Header The header with the specified name.
     */
    public function getHeader(string $name): Header;

    /**
     * Returns all the headers from the collection.
     *
     * @return Header[] An associative array of the collection headers where
     * each key is a header name and each value is the {@link Header}
     * instance of this name.
     */
    public function getHeaders(): array;
}
