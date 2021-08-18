<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Cookie;

/**
 * Class CookieImpl
 *
 * This class is the default implementation of {@link Cookie}.
 */
class CookieImpl implements Cookie
{
    /**
     * The name of the cookie.
     *
     * @var string The cookie name.
     */
    private string $name;

    /**
     * The value of the cookie.
     *
     * @var string The cookie value.
     */
    private string $value;

    /**
     * CookieImpl constructor.
     *
     * @param string $name The cookie name.
     * @param string $value The cookie value.
     */
    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
