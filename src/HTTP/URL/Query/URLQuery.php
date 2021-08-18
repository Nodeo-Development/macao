<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\URL\Query;

use JetBrains\PhpStorm\Pure;

/**
 * Interface URLQuery
 *
 * This interface represents the query component of the URI.
 * It is indicated by the first question mark "?" and ends with the start
 * of the fragment part.
 *
 * The query component format is not standardized but usually consists
 * of a list of key-value pairs separated by the character '&'.
 * This is the format that will be used in this application.
 *
 * Please report to the RFC 3986 specification for further details about URL
 * queries.
 *
 * A Query is considered an immutable object.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc3986#section-3.4
 */
interface URLQuery
{
    /**
     * Checks whether the query is empty.
     *
     * @return bool <code>true</code> if the query contains no parameters,
     * <code>false</code> otherwise.
     */
    #[Pure]
    public function isEmpty(): bool;

    /**
     * Returns the parameters of the query.
     *
     * @return string[] The query parameters as an associative array. Each key
     * is a parameter name and each value is the parameter value.
     */
    #[Pure]
    public function getParameters(): array;

    /**
     * Checks if the query contains the specified parameter.
     *
     * @param string $parameter The parameter name.
     * @return bool <code>true</code> if the query contains the specified
     * parameter, <code>false</code> otherwise.
     */
    #[Pure]
    public function hasParameter(string $parameter): bool;

    /**
     * Returns the value associated to the specified parameter.
     *
     * @param string $name The parameter name.
     * @return string The value associated to the specified parameter.
     */
    public function getParameter(string $name): string;

    /**
     * Checks if the query equals the specified query.
     *
     * @param string $query The query to compare the query to.
     * @return bool <code>true</code> if the query equals the specified
     * query, <code>false</code> otherwise.
     */
    #[Pure]
    public function equals(string $query): bool;

    /**
     * Returns the string representation of the query.
     *
     * @return string The query string representation, or an empty string if
     * the query is empty.
     */
    #[Pure]
    public function __toString(): string;
}
