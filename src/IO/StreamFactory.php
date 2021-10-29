<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\IO;

use InvalidArgumentException;
use RuntimeException;

/**
 * Class StreamFactory
 *
 * This class contains several methods to create a {@link Stream}
 * instance.
 *
 * @see Stream
 */
class StreamFactory
{
    private const TEMPORARY_FILE_NAME = 'php://temp';
    private const TEMPORARY_FILE_OPEN_MODE = 'r+';

    /**
     * @var array An array of allowed open modes
     */
    private const MODES = [
        'r',
        'rb',
        'rt',
        'w',
        'wb',
        'wt',
        'a',
        'ab',
        'at',
        'x',
        'xb',
        'xt',
        'c',
        'cb',
        'ct',
        'r+',
        'r+b',
        'r+t',
        'w+',
        'w+b',
        'w+t',
        'a+',
        'a+b',
        'a+t',
        'x+',
        'x+b',
        'x+t',
        'c+',
        'c+b',
        'c+t'
    ];

    /**
     * Creates a new stream with the specified content.
     * The content will be wrapped in a temporary resource.
     * By default, as the content is appended to an empty file, the
     * file cursor will be at the end of the stream.
     *
     * @param string $content The stream contents.
     * @return Stream The created {@link Stream} instance
     * with the specified content.
     * @throws RuntimeException If an error occurred
     * while wrapping the content in a temporary resource.
     */
    public function createStream(string $content): Stream
    {
        $stream = $this->createStreamFromFile(
            self::TEMPORARY_FILE_NAME,
            self::TEMPORARY_FILE_OPEN_MODE
        );

        $stream->write($content);

        return $stream;
    }

    /**
     * Creates a new stream with the content of the specified file.
     *
     * @param string $filename The file name.
     * @param string $mode [optional] The open mode. If the open mode is not
     * specified, it will be set on <code>'r'</code> (read-only).
     * @return Stream The created {@link Stream} instance
     * with the specified file contents.
     */
    public function createStreamFromFile(
        string $filename,
        string $mode = 'r'
    ): Stream {
        if (!in_array($mode, self::MODES)) {
            throw new InvalidArgumentException(
                'The specified open mode is invalid'
            );
        }

        $resource = fopen($filename, $mode);

        if ($resource === false) {
            throw new RuntimeException('The file cannot be opened');
        }

        return $this->createStreamFromResource($resource);
    }

    /**
     * Creates a new stream with the content of the specified resource.
     *
     * @param resource $resource The resource to fill the stream with.
     * @return Stream The created {@link Stream} instance
     * with the specified resource contents.
     */
    public function createStreamFromResource($resource): Stream
    {
        return new StreamImpl($resource);
    }
}
