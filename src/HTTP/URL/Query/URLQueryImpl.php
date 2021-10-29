<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\URL\Query;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;

/**
 * Class URLQueryImpl
 *
 * This class is the default implementation of {@link URLQuery}.
 */
class URLQueryImpl implements URLQuery
{
    /**
     * The query parameters.
     *
     * @var array An associative array of parameters where each key is a
     * parameter name and each value is the parameter value.
     */
    private array $parameters;

    /**
     * URLQueryImpl constructor.
     *
     * @param string $query The query string.
     * An empty string is equivalent to an empty query.
     */
    public function __construct(string $query)
    {
        $this->setQuery($query);
    }

    /**
     * Changes the query string.
     *
     * @param string $query The new query string.
     * An empty string is equivalent to empty the query.
     */
    private function setQuery(string $query)
    {
        if (empty($query)) {
            $this->parameters = [];
        } else {
            parse_str($query, $parameters);

            foreach ($parameters as $name => $value) {
                $this->parameters[$name] = $value;
            }
        }
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function isEmpty(): bool
    {
        return empty($this->parameters);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function hasParameter(string $parameter): bool
    {
        return array_key_exists($parameter, $this->parameters);
    }

    /**
     * @inheritDoc
     */
    public function getParameter(string $name): string
    {
        if (!$this->hasParameter($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The query doesnt contain any parameter with name %s',
                    $name
                )
            );
        }

        return $this->parameters[$name];
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function equals(string $query): bool
    {
        return $query === $this->__toString();
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function __toString(): string
    {
        if (empty($this->parameters)) {
            return '';
        } else {
            return '?' . $this->getParametersLine();
        }
    }

    /**
     * Returns the parameters of the query as a string.
     *
     * @return string The query parameters as a list of key-value pairs
     * separated by the character '&'.
     */
    private function getParametersLine(): string
    {
        return http_build_query($this->parameters);
    }
}
