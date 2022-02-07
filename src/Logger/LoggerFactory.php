<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Macao\Logger;

use Macao\Logger\Loader\Loader;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

/**
 * Contains various methods to create a {@link Logger} instance.
 */
class LoggerFactory
{
    /**
     * Creates a logger from the specified log file with the specified format.
     *
     * @param resource  $file   The logger file.
     * @param LogFormat $format The logger format.
     *
     * @return Logger The instance of the created logger.
     */
    #[Pure]
    public function from(
        $file,
        LogFormat $format
    ): Logger {
        return new LoggerImpl($file, $format);
    }

    /**
     * Creates a logger from the specified log file with the specified format.
     *
     * @param resource    $file       The logger file.
     * @param string|null $format     The logger format.
     * @param string|null $dateFormat The logger date format.
     *
     * @return Logger The instance of the created logger.
     */
    #[Pure]
    public function fromResource(
        $file,
        ?string $format = null,
        ?string $dateFormat = null
    ): Logger {
        $format = new LogFormat($format, $dateFormat);

        return $this->from($file, $format);
    }

    /**
     * Creates a logger from the specified log filename with the specified format.
     *
     * @param resource    $filename   The logger filename.
     * @param string|null $format     The logger format.
     * @param string|null $dateFormat The logger dateformat.
     *
     * @return Logger The instance of the created logger.
     */
    public function fromFile(
        $filename,
        ?string $format = null,
        ?string $dateFormat = null,
    ): Logger {
        $logFile = fopen($filename, 'w+');

        return $this->fromResource($logFile, $format, $dateFormat);
    }

    /**
     * Creates a logger from the specified properties.
     *
     * @param array $properties An associative array of the logger properties.
     *                          It can contain the following items :
     *                          <ul>
     *                          <li>file(string) : the logger filename</li>
     *                          <li>format(string) [optional] : the logger format</li>
     *                          <li>dateFormat(string) [optional] : the logger date format</li>
     *                          </ul>
     *
     * @return Logger The instance of the created logger.
     */
    public function fromProperties(
        #[ArrayShape([
            'file'       => 'string',
            'format'     => 'string',
            'dateFormat' => 'string',
        ])] array $properties
    ): Logger {
        if (!isset($properties['file'])) {
            throw new InvalidArgumentException(
                'The logger filename must be set and not null'
            );
        }

        $logFilename = $properties['file'];

        $format = $properties['format'] ?? null;
        $dateFormat = $properties['dateFormat'] ?? null;

        return $this->fromFile($logFilename, $format, $dateFormat);
    }

    /**
     * Creates a logger from the specified loader.
     *
     * @param Loader $loader The logger loader.
     *
     * @return Logger The instance of the created logger.
     */
    public function fromConfigurationLoader(Loader $loader): Logger
    {
        $properties = $loader->load();

        return $this->fromProperties($properties);
    }
}