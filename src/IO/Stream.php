<?php

/*
 * Copyright (c) Nodeo - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Written by Juan <juan.valero@nodeo.fr>, 2021
 */

namespace Macao\IO;

use InvalidArgumentException;
use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Pure;

/**
 * Interface Stream
 *
 * This interface represents a data stream.
 *
 * @see https://www.php.net/manual/fr/book.stream.php
 */
interface Stream
{
    /**
     * Closes the stream and any underlying resources.
     */
    public function close();

    /**
     * Returns the size of the stream.
     *
     * @return int The stream size in bytes.
     */
    public function getSize(): int;

    /**
     * Returns the current position of the file pointer.
     *
     * @return int The current file pointer position.
     */
    public function tell(): int;

    /**
     * Returns whether the stream is at the end.
     *
     * @return bool <code>true</code> if the stream is at the end of if an
     * error occurred, <code>false</code> otherwise.
     */
    public function eof(): bool;

    /**
     * Returns whether the stream is seekable.
     *
     * @return bool <code>true</code> if the stream is seekable,
     * <code>false</code> if it is not or if the stream is closed.
     */
    #[Pure]
    public function isSeekable(): bool;

    /**
     * Seeks to the specified position in the stream.
     *
     * @see http://www.php.net/manual/en/function.fseek.php
     * @param int $offset The position to seek to.
     * @param int $whence [optional] The method calculation.
     * If no method calculation is specified, it will be set to
     * <code>SEEK_SET</code>.
     * @throws InvalidArgumentException If the specified offset is a negative
     * integer.
     * @throws InvalidArgumentException If the specified whence is invalid.
     */
    public function seek(
        int $offset,
        #[ExpectedValues([
            SEEK_SET,
            SEEK_CUR,
            SEEK_END
        ])] int $whence = SEEK_SET
    );

    /**
     * Seeks to the beginning of the stream.
     */
    public function rewind();

    /**
     * Returns whether the stream is readable.
     *
     * @return bool <code>true</code> if the stream is readable,
     * <code>false</code> if it is not or if the stream is closed.
     */
    #[Pure]
    public function isReadable(): bool;

    /**
     * Reads data from the stream.
     * At most $length bytes will be read. Fewer bytes can be read if no more
     * bytes are available.
     *
     * @param ?int $length [optional] The maximal number of bytes to read.
     * If the length is not specified or is null, all the file will be read.
     * @return string The data read from the stream.
     */
    public function read(?int $length = null): string;

    /**
     * Returns the remaining contents of the stream.
     *
     * @return string The stream remaining contents.
     */
    public function getContents(): string;

    /**
     * Returns whether the stream is writable.
     *
     * @return bool <code>true</code> if the stream is writable,
     * <code>false</code> if it is not or if the stream is closed.
     */
    #[Pure]
    public function isWritable(): bool;

    /**
     * Writes data to the stream.
     * At most $length bytes will be written. Fewer bytes can be written if the
     * stream is at its end.
     *
     * @param string $data The data to be written.
     * @param int|null $length [optional] The data length to be written.
     * If the length is not specified or is null, all the data will be written.
     * @return int The number of bytes written to the stream.
     */
    public function write(string $data, ?int $length = null): int;

    /**
     * Truncates the stream.
     *
     * @param int|null $size [optional] The size to truncate to.
     * If the size is not specified or is null, all the file will be
     * truncated.
     */
    public function truncate(?int $size = null);

    /**
     * Returns the metadata of the stream.
     *
     * @see http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string|null $key [optional] The specific metadata to return.
     * If the key is not specified or is null, all the metadata will be
     * returned.
     * @return array|string An associative array if no specific metadata is
     * provided, the specific provided metadata value otherwise.
     * @throws InvalidArgumentException If the specified metadata is invalid.
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
            'uri'
        ])] ?string $key = null
    ): array|string;

    /**
     * Returns the contents of the stream.
     *
     * @return string The stream contents, or an empty string if an error
     * occurred.
     */
    public function __toString(): string;
}
