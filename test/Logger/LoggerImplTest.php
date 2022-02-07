<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Test\Logger;

use Macao\Logger\Loader\IniLoader;
use Macao\Logger\LoggerFactory;
use Macao\Logger\LogLevel;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoggerImplTest extends TestCase
{
    private readonly LoggerFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new LoggerFactory();
    }

    public function testLoggerWithResourceLogFile(): void
    {
        $logFile = tmpfile();

        $logger = $this->factory->fromResource($logFile);

        $logger->log('Hello world !', LogLevel::DEBUG);

        $contents = stream_get_contents($logFile, -1, 0);
        self::assertEquals('[DEBUG] : Hello world !', $contents);
    }

    public function testLoggerWithLogFile(): void
    {
        $logFile = fopen('php://temp', 'w+');

        $logger = $this->factory->fromResource($logFile);

        $logger->log('Hello world !', LogLevel::INFO);

        $contents = stream_get_contents($logFile, -1, 0);
        self::assertEquals('[INFO] : Hello world !', $contents);
    }

    public function testLogWithoutFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $properties = [
            'format' => '[%level%] : %message%',
        ];

        $this->factory->fromProperties($properties);
    }

    public function testLogWithLoader(): void
    {
        $loader = new IniLoader(
            ConfigurationUtils::getConfigurationFilePath('a.ini')
        );

        $logger = $this->factory->fromConfigurationLoader($loader);

        $logger->log('Hello world !', LogLevel::INFO);

        $contents = file_get_contents(
            '/tmp/Cafiaso/log.txt'
        );

        $date = date('d/m/Y');
        self::assertEquals('[INFO] >> Hello world ! - '.$date, $contents);
    }
}
