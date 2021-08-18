<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\URL;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Macao\HTTP\URL\Query\URLQuery;
use Macao\HTTP\URL\Query\URLQueryImpl;

/**
 * Class URLImpl
 *
 * This class is the default implementation of {@link URL}.
 */
class URLImpl implements URL
{
    private const PORT_LOWER_LIMIT = 0;
    private const PORT_UPPER_LIMIT = 65535;

    private const SCHEME_STANDARD_PORTS = [
        'http' => 80,
        'https' => 443
    ];

    /**
     * The scheme component of the URL.
     *
     * @var string The URL scheme, or an empty string if no scheme was
     * specified.
     */
    private string $scheme;

    /**
     * The user component of the URL.
     *
     * @var string The URL user, or an empty string if no user was specified.
     */
    private string $user;

    /**
     * The host component of the URL.
     *
     * @var string The URL host, or an empty string if no host was specified.
     */
    private string $host;

    /**
     * The port component of the URL.
     *
     * @var int|null The URL port, or null if no port was specified.
     */
    private ?int $port;

    /**
     * The path component of the URL.
     *
     * @var string The URL path, or an empty string if no path was specified.
     */
    private string $path;

    /**
     * The query component of the URL.
     *
     * @see URLQuery
     * @var URLQuery The URL query.
     */
    private URLQuery $query;

    /**
     * The fragment component of the URL.
     *
     * @var string The URL fragment, or an empty string if no fragment was
     * specified.
     */
    private string $fragment;

    /**
     * URL constructor.
     *
     * @param string $scheme The URL scheme.
     * An empty string is equivalent to an empty scheme.
     * @param string $user The URL user.
     * An empty string is equivalent to an empty user.
     * @param string $host The URL host.
     * An empty string is equivalent to an empty host.
     * @param int|null $port The URL port.
     * A null value is equivalent to an empty port.
     * @param string $path The URL path.
     * An empty string is equivalent to an empty path.
     * @param string $query The URL query.
     * An empty string is equivalent to an empty query.
     * @param string $fragment The URL fragment.
     * An empty string is equivalent to an empty fragment.
     */
    public function __construct(
        string $scheme,
        string $user,
        string $host,
        ?int $port,
        string $path,
        string $query,
        string $fragment
    ) {
        $this->setScheme($scheme);
        $this->setUser($user);
        $this->setHost($host);
        $this->setPort($port);
        $this->setPath($path);
        $this->setQuery($query);
        $this->setFragment($fragment);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * Changes the scheme component of the URL.
     *
     * @param string $scheme The URL scheme.
     * An empty string is equivalent to removing the scheme.
     */
    private function setScheme(string $scheme)
    {
        if (!array_key_exists($scheme, self::SCHEME_STANDARD_PORTS)) {
            throw new InvalidArgumentException(
                'The specified scheme is invalid.' .
                ' It must be either "http" or "https"'
            );
        }

        $this->scheme = $this->normalize($scheme); // Scheme should be
        // normalized as specified by the RFC 3986
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getAuthority(): string
    {
        $host = $this->getHost();

        if (empty($host)) {
            return '';
        }

        $authority = '';

        $userInfo = $this->getUser();
        if (!empty($userInfo)) {
            $authority .= $userInfo . '@';
        }

        $authority .= $host;

        $port = $this->getPort();

        if (!is_null($port)) {
            $authority .= ':' . $port;
        }

        return $authority;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * Changes the user component of the URL.
     *
     * @param string $user The URL user.
     * An empty string is equivalent to removing the user.
     */
    private function setUser(string $user)
    {
        $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Changes the host component of the URL.
     *
     * @param string $host The URL host.
     * An empty string is equivalent to removing the host.
     */
    private function setHost(string $host)
    {
        $this->host = $this->normalize($host);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getPort(): ?int
    {
        return $this->port;
    }

    /**
     * Changes the port component of the URL.
     *
     * @param int|null $port The URL port.
     * A null value is equivalent to removing the port.
     */
    private function setPort(?int $port)
    {
        if (is_int($port)) {
            if (
                $port < self::PORT_LOWER_LIMIT
                || $port > self::PORT_UPPER_LIMIT
            ) {
                throw new InvalidArgumentException(
                    sprintf(
                        'The given port is outside bounds.' .
                        ' It must be between %d and %d',
                        self::PORT_LOWER_LIMIT,
                        self::PORT_UPPER_LIMIT
                    )
                );
            }
        }

        $standardPort = self::SCHEME_STANDARD_PORTS[$this->getScheme()];

        if ($port === $standardPort) {
            // Standard port for the scheme
            $this->port = null;
        } else {
            $this->port = $port;
        }
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Changes the path component of the URL.
     *
     * @param string $path The URL path.
     * An empty string is equivalent to removing the path.
     */
    private function setPath(string $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getQuery(): URLQuery
    {
        return $this->query;
    }

    /**
     * Changes the query component of the URL.
     *
     * @param string $query The URL query.
     * An empty string is equivalent to removing the query.
     */
    private function setQuery(string $query)
    {
        $this->query = new URLQueryImpl($query);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getFragment(): string
    {
        return $this->fragment;
    }

    /**
     * Changes the fragment component of the URL.
     *
     * @param string $fragment The URL fragment.
     * An empty string is equivalent to removing the fragment.
     */
    private function setFragment(string $fragment)
    {
        $this->fragment = $fragment;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function __toString(): string
    {
        $uri = '';

        $scheme = $this->getScheme();

        if (!empty($scheme)) {
            $uri .= $scheme . ':';
        }

        $authority = $this->getAuthority();

        if (!empty($authority)) {
            $uri .= '//' . $authority;
        }

        $path = $this->getPath();

        if (!empty($path)) {
            if ($path[0] === '/') {
                // Absolute path
                if (empty($authority)) {
                    $path = ltrim(
                        $path,
                        '/'
                    ); // Reduces starting slashes to one
                }
            } elseif (!empty($authority)) {
                $path = '/' . $path; // Prefixes the path by '/'
            }
        }

        $uri .= $path;

        $query = $this->getQuery();

        if (!$query->isEmpty()) {
            $uri .= $query->__toString();
        }

        $fragment = $this->getFragment();

        if (!empty($fragment)) {
            $uri .= '#' . $fragment;
        }

        return $uri;
    }

    /**
     * Normalizes the specified component.
     *
     * @param string $component The component to normalize.
     * @return string The normalized component.
     */
    #[Pure]
    private function normalize(string $component): string
    {
        return strtolower($component);
    }
}
