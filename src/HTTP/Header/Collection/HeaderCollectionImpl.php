<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\HTTP\Header\Collection;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use Macao\HTTP\Cookie\Cookie;
use Macao\HTTP\Header\Header;
use Traversable;

/**
 * Class HeaderCollectionImpl
 *
 * This class is the default implementation of {@link HeaderCollection}.
 */
class HeaderCollectionImpl extends ArrayIterator implements HeaderCollection
{
    /**
     * The headers of the collection.
     *
     * @var Header[] An associative array of the collection headers where each
     * key is a header name and each value is the {@link Header} instance of
     * this name.
     */
    private array $headers;

    /**
     * HeaderCollectionImpl constructor.
     *
     * @param Header[] $headers An associative array of the collection headers
     * where each key is a header name and each value is the {@link Header}
     * instance of this name.
     */
    public function __construct(array $headers)
    {
        parent::__construct($headers);

        $this->headers = $headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    /**
     * @inheritDoc
     */
    public function getHeader(string $name): Header
    {
        if (!$this->hasHeader($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The collection doesnt contain any header with name %s',
                    $name
                )
            );
        }

        return $this->headers[$name];
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
