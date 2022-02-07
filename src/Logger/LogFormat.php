<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Macao\Logger;

/**
 * Describes the format used to create the log.
 *
 * Various properties can be used to format the log :
 * <ul>
 *  <li>level : the log level</li>
 *  <li>message : the log message</li>
 *  <li>date : the log date</li>
 * </ul>
 *
 * Each property must be quoted between '%'.
 *
 * The date format can also be specified.
 *
 * Example :
 *
 * With this format :
 * <code>
 *  [%level%] - %date >> %message%
 * </code>
 *
 * And this date format : <code>d/m/Y</code>
 *
 * A severe log "Error" written the 04/12/2021 will be rendering like so :
 * <code>[SEVERE] - 04/12/2021 >> Error</code>
 *
 * @see date()
 */
class LogFormat
{
    private const DEFAULT_FORMAT = '[%level%] : %message%';
    private const DEFAULT_DATE_FORMAT = DATE_RFC7231;

    /**
     * @var string The log format
     */
    private readonly string $format;

    /**
     * @var string The log date format
     */
    private readonly string $dateFormat;

    /**
     * LogFormat constructor.
     *
     * @param string|null $format     The log format.
     * @param string|null $dateFormat The log date format.
     */
    public function __construct(
        ?string $format = null,
        ?string $dateFormat = null
    ) {
        $this->format = $format ?? self::DEFAULT_FORMAT;
        $this->dateFormat = $dateFormat ?? self::DEFAULT_DATE_FORMAT;
    }

    /**
     * Formats the specified log message with the specified log level.
     *
     * @param string   $message The log message.
     * @param LogLevel $level   The log level.
     * @param int      $time    The log time.
     *
     * @return string The formatted log.
     */
    public function format(
        string $message,
        LogLevel $level,
        int $time
    ): string {
        $date = date($this->dateFormat, $time);

        return str_replace(
            ['%level%', '%message%', '%date%'],
            [$level->name, $message, $date],
            $this->format
        );
    }
}
