<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Header;

use JetBrains\PhpStorm\Pure;

/**
 * Interface Header
 *
 * This interface represents a header field as specified by the RFC 7230.
 *
 * Please refer to the RFC specification for further details about headers.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc7230#section-3.2
 */
interface Header
{
    /**
     * Returns the name of the header.
     *
     * @return string The header name.
     */
    #[Pure]
    public function getName(): string;

    /**
     * Returns the values of the header.
     *
     * @return string[] A string array where each value is one of the header
     * value.
     */
    #[Pure]
    public function getValue(): array;

    /**
     * Returns the header value as a string.
     *
     * @return string The header value where each value is separated by a
     * comma, or an empty string if the header doesn't contain any value.
     */
    #[Pure]
    public function getHeaderLine(): string;

    /**
     * Returns the string representation of the header.
     *
     * @return string The header string representation as "name: value" format.
     */
    #[Pure]
    public function __toString(): string;
}
