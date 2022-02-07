<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Test\Logger;

use Macao\Logger\LogFormat;
use Macao\Logger\LoggerFactory;
use Macao\Logger\LogLevel;
use DateTime;
use PHPUnit\Framework\TestCase;

class LogFormatTest extends TestCase
{
    protected function setUp(): void
    {
        $this->factory = new LoggerFactory();
    }

    public function testFormat(): void
    {
        $format = new LogFormat(
            '[%level%] : %message%',
            'd'
        );

        self::assertEquals(
            '[SEVERE] : A message',
            $format->format('A message', LogLevel::SEVERE, time())
        );
    }

    public function testDateFormatWithCurrentTime(): void
    {
        $format = new LogFormat(
            '[%level%] : %message% - %date%',
            'd/m/Y'
        );

        self::assertEquals(
            '[INFO] : A message - '.date('d/m/Y'),
            $format->format('A message', LogLevel::INFO, time())
        );
    }

    public function testDateFormatWithSpecifiedTime(): void
    {
        $format = new LogFormat(
            '[%level%] : %message% - %date%',
            'd/m/Y'
        );

        $time = DateTime::createFromFormat('d/m/Y', '04/10/2001')
            ->getTimestamp();

        self::assertEquals(
            '[INFO] : A message - 04/10/2001',
            $format->format('A message', LogLevel::INFO, $time)
        );
    }
}
