<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\URL;

use JetBrains\PhpStorm\Pure;
use Macao\HTTP\URL\Query\URLQuery;

/**
 * Interface URL
 *
 * This interface represents a URL associated with an HTTP request, as
 * specified by the PSR-7 rule and the RFC 3986.
 *
 * Please report to the RFC 3986 specification for further details about URLs.
 *
 * A URL is considered an immutable object.
 *
 * @see https://tools.ietf.org/html/rfc3986
 */
interface URL
{
    /**
     * Returns the scheme component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URL scheme or an empty string if no scheme is
     * present.
     */
    #[Pure]
    public function getScheme(): string;

    /**
     * Returns the authority component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URL authority, or an empty string if no authority
     * is present.
     */
    #[Pure]
    public function getAuthority(): string;

    /**
     * Returns the user information component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.1
     * @return string The URL user information, or an empty string if no user
     * information is present.
     */
    #[Pure]
    public function getUser(): string;

    /**
     * Returns the host component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URL host, or an empty string if no host is
     * present.
     */
    #[Pure]
    public function getHost(): string;

    /**
     * Returns the port component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2.3
     * @return int|null The URL port, or null if no port is present.
     */
    #[Pure]
    public function getPort(): ?int;

    /**
     * Returns the path component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URL path, or an empty string if no path is present.
     */
    #[Pure]
    public function getPath(): string;

    /**
     * Returns the query component of the URL.
     *
     * @see URLQuery
     * @return URLQuery The URL query.
     */
    #[Pure]
    public function getQuery(): URLQuery;

    /**
     * Returns the fragment component of the URL.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URL fragment, or an empty string if no fragment
     * is present.
     */
    #[Pure]
    public function getFragment(): string;

    /**
     * Returns the string representation of the URL.
     *
     * @return string The URL string representation, or an empty string if
     * the URL is empty.
     */
    #[Pure]
    public function __toString(): string;
}
