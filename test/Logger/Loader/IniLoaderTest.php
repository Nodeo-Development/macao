<?php
/*
 * This file is part of Cafiaso.
 *
 * Copyright (c) 2022, Juan Valero <juan.valero@cafiaso.com>
 *
 * See the LICENSE file for full license details.
 */

namespace Test\Logger\Loader;

use Macao\Logger\Loader\IniLoader;
use PHPUnit\Framework\TestCase;
use Test\Logger\ConfigurationUtils;

class IniLoaderTest extends TestCase
{
    public function testCompleteFile(): void
    {
        $loader = new IniLoader(
            ConfigurationUtils::getConfigurationFilePath("a.ini")
        );

        $properties = $loader->load();

        self::assertArrayHasKey('file', $properties);
        self::assertEquals('/tmp/Cafiaso/log.txt', $properties['file']);

        self::assertArrayHasKey('format', $properties);
        self::assertEquals(
            '[%level%] >> %message% - %date%',
            $properties['format']
        );

        self::assertArrayHasKey('dateFormat', $properties);
        self::assertEquals('d/m/Y', $properties['dateFormat']);
    }

    public function testIncompleteFile(): void
    {
        $loader = new IniLoader(
            ConfigurationUtils::getConfigurationFilePath("b.ini")
        );

        $properties = $loader->load();

        self::assertArrayHasKey('file', $properties);
        self::assertEquals('/tmp/Cafiaso/log.txt', $properties['file']);

        self::assertArrayNotHasKey('format', $properties);

        self::assertArrayNotHasKey('dateFormat', $properties);
    }

    public function testMultipleCategoriesFile(): void
    {
        $loader = new IniLoader(
            ConfigurationUtils::getConfigurationFilePath('c.ini')
        );

        $properties = $loader->load();

        self::assertArrayNotHasKey('foo', $properties);
        self::assertArrayNotHasKey('bar', $properties);

        self::assertArrayNotHasKey('logger', $properties);

        self::assertArrayHasKey('file', $properties);
        self::assertArrayHasKey('format', $properties);
        self::assertArrayHasKey('dateFormat', $properties);
    }
}
