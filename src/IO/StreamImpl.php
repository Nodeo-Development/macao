<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\IO;

use Exception;
use InvalidArgumentException;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;
use RuntimeException;

/**
 * Class StreamImpl
 *
 * This class is the default {@link Stream} implementation.
 */
class StreamImpl implements Stream
{
    private const READABLE_WRITABLE_HASH = [
        'readable_only' => [
            'r',
            'rb',
            'rt'
        ],
        'writable_only' => [
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
            'ct'
        ],
        'readable_writable' => [
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
            'c+t',
        ]
    ];

    private bool $closed;

    private $stream;

    private bool $seekable;

    private bool $readable;

    private bool $writable;

    private ?int $size;

    /**
     * Stream constructor.
     *
     * @param resource $resource
     * @throws InvalidArgumentException If the specified resource is not a
     * valid resource.
     */
    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new InvalidArgumentException(
                'The specified resource is not a valid resource'
            );
        }

        $this->closed = false;

        $this->stream = $resource;

        $metadata = $this->getMetadata();

        $this->seekable = $metadata['seekable'];

        $mode = $metadata['mode'];

        $this->readable = in_array(
            $mode,
            self::READABLE_WRITABLE_HASH['readable']
        ) || in_array(
            $mode,
            self::READABLE_WRITABLE_HASH['readable_writable']
        );

        $this->writable = in_array(
            $mode,
            self::READABLE_WRITABLE_HASH['writable']
        ) || in_array(
            $mode,
            self::READABLE_WRITABLE_HASH['readable_writable']
        );

        $this->updateSize();
    }

    /**
     * Stream destructor.
     */
    public function __destruct()
    {
        if (!$this->isClosed()) {
            $this->close();
        }
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
        if ($this->isClosed()) {
            throw new RuntimeException('The stream is already closed');
        }

        if (fclose($this->stream) === false) {
            throw new RuntimeException(
                'An exception occurred while closing the stream'
            );
        }

        $this->detach();

        $this->closed = true;
    }

    /**
     * Detach any underlying resources from the stream.
     */
    private function detach()
    {
        $this->stream = null;

        $this->size = null;
        $this->readable = $this->writable = $this->seekable = false;
    }

    /**
     * @inheritDoc
     */
    public function getSize(): int
    {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot get the stream size because the stream is closed'
            );
        }

        if (is_null($this->size)) {
            // Try to update the size
            $this->updateSize();
        }

        return $this->size;
    }

    /**
     * Updates the stream size.
     */
    private function updateSize()
    {
        $stats = fstat($this->stream);

        if ($stats !== false) {
            $this->size = $stats['size'];
        }
    }

    /**
     * @inheritDoc
     */
    public function tell(): int
    {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot tell the current pointer position because the stream is closed'
            );
        }

        $tell = ftell($this->stream);

        if ($tell === false) {
            throw new RuntimeException(
                'An error occurred while getting the current pointer position'
            );
        }

        return $tell;
    }

    /**
     * @inheritDoc
     */
    public function eof(): bool
    {
        return $this->isClosed() || feof($this->stream);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * @inheritDoc
     */
    public function seek(
        int $offset,
        #[ExpectedValues([
            SEEK_SET,
            SEEK_CUR,
            SEEK_END
        ])] int $whence = SEEK_SET
    ) {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot seek in the stream because the stream is closed'
            );
        }

        if (!$this->isSeekable()) {
            throw new RuntimeException('The stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException(
                'An error occurred while seeking the stream'
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        $this->seek(0);
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function isReadable(): bool
    {
        return $this->readable;
    }

    /**
     * @inheritDoc
     */
    public function read(?int $length = null): string
    {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot read from the stream because the stream is closed'
            );
        }

        if (!$this->isReadable()) {
            throw new RuntimeException('The stream is not readable');
        }

        if (is_null($length)) {
            $length = $this->getSize();
        }

        $data = fread($this->stream, $length);

        if ($data === false) {
            throw new RuntimeException(
                'An error occurred while reading from the stream'
            );
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function getContents(): string
    {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot get the stream contents because the stream is closed'
            );
        }

        $contents = stream_get_contents($this->stream);

        if ($contents === false) {
            throw new RuntimeException(
                'An error occurred while getting the stream contents'
            );
        }

        return $contents;
    }

    /**
     * @inheritDoc
     */
    public function getMetadata(
        #[ExpectedValues([
            'timed_out',
            'blocked',
            'eof',
            'unread_bytes',
            'stream_type',
            'wrapper_type',
            'wrapper_data',
            'mode',
            'seekable',
            'uri',
            null
        ])] string $key = null
    ): array|string {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot get the stream metadata because the stream is closed'
            );
        }

        $metadata = stream_get_meta_data($this->stream);

        if ($key === null) {
            return $metadata;
        } else {
            if (!array_key_exists($key, $metadata)) {
                throw new InvalidArgumentException(
                    'The specified key is invalid'
                );
            }

            return $metadata[$key];
        }
    }

    /**
     * @inheritDoc
     */
    #[Pure]
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * @inheritDoc
     */
    public function write(string $data, ?int $length = null): int
    {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot write to the stream because the stream is closed'
            );
        }

        if (!$this->isWritable()) {
            throw new RuntimeException('The stream is not writable');
        }

        $bytes = fwrite($this->stream, $data, $length);

        if ($bytes === false) {
            throw new RuntimeException(
                'An error occurred while writing to the stream'
            );
        }

        $this->size = null; // Update the size

        return $bytes;
    }

    /**
     * @inheritDoc
     */
    public function truncate(?int $size = null): void
    {
        if ($this->isClosed()) {
            throw new RuntimeException(
                'Cannot truncate the stream because the stream is closed'
            );
        }

        if (ftruncate($this->stream, $size) === false) {
            throw new RuntimeException(
                'An error occurred while truncating the stream'
            );
        }

        $this->size = null;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        try {
            $this->rewind();

            return $this->getContents();
        } catch (Exception) {
            return '';
        }
    }

    /**
     * Checks whether the stream is closed.
     *
     * @return bool <code>true</code> if the stream is closed,
     * <code>false</code> otherwise.
     */
    #[Pure]
    private function isClosed(): bool
    {
        return $this->closed;
    }
}
