<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Header;

use JetBrains\PhpStorm\Pure;

/**
 * Class HeaderImpl
 *
 * This class is the default implementation of {@link Header}.
 */
class HeaderImpl implements Header
{
    /**
     * The name of the header.
     *
     * @var string The header name.
     */
    private string $name;

    /**
     * The value of the header.
     *
     * @var string[] The header value.
     */
    private array $values;

    /**
     * Header constructor.
     *
     * @param string $name The header name.
     * @param string[] $values The header value.
     */
    #[Pure]
    public function __construct(string $name, array $values)
    {
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getValue(): array
    {
        return $this->values;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getHeaderLine(): string
    {
        return implode(',', $this->getValue());
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function __toString(): string
    {
        return $this->getName() . ':' . $this->getValue();
    }
}
