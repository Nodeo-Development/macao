<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Cookie\Collection;

use ArrayIterator;
use InvalidArgumentException;
use Macao\HTTP\Cookie\Cookie;

/**
 * Class CookieCollectionImpl
 *
 * This class is the default implementation of {@link CookieCollection}.
 */
class CookieCollectionImpl extends ArrayIterator implements CookieCollection
{
    /**
     * The cookies.
     *
     * @var array An associative array of the cookies where each key is a
     * cookie name and each value is the {@link Cookie} instance of this name.
     */
    private array $cookies;

    /**
     * CookieCollectionImpl constructor.
     *
     * @param Cookie[] $headers An associative array where each key is a cookie
     * name and each value is the {@link Cookie} instance of this name.
     */
    public function __construct(array $headers)
    {
        parent::__construct($headers);

        $this->cookies = $headers;
    }

    /**
     * @inheritDoc
     */
    public function hasCookie(string $name): bool
    {
        return array_key_exists($name, $this->cookies);
    }

    /**
     * @inheritDoc
     */
    public function getCookie(string $name): Cookie
    {
        if (!$this->hasCookie($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The collection doesnt contain any cookie with name %s',
                    $name
                )
            );
        }

        return $this->cookies[$name];
    }

    /**
     * @inheritDoc
     */
    public function getCookies(): array
    {
        return $this->cookies;
    }
}
