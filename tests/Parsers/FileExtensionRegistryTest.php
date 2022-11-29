<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Parsers;

use Fi1a\Config\Exceptions\InvalidArgumentException;
use Fi1a\Config\Parsers\FileExtensionRegistry;
use Fi1a\Config\Parsers\PHPParser;
use PHPUnit\Framework\TestCase;

/**
 * Реестр парсеров по расширениям файлов
 */
class FileExtensionRegistryTest extends TestCase
{
    /**
     * Добавить
     */
    public function testAdd(): void
    {
        $this->assertTrue(FileExtensionRegistry::add('test1', PHPParser::class));
        $this->assertFalse(FileExtensionRegistry::add('test1', PHPParser::class));
    }

    /**
     * Получение
     *
     * @depends testAdd
     */
    public function testAddException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        FileExtensionRegistry::add('test2', static::class);
    }

    /**
     * Наличие
     *
     * @depends testAdd
     */
    public function testHas(): void
    {
        $this->assertFalse(FileExtensionRegistry::has('unknown'));
        $this->assertTrue(FileExtensionRegistry::has('test1'));
    }

    /**
     * Получение
     *
     * @depends testAdd
     */
    public function testGet(): void
    {
        $this->assertEquals(PHPParser::class, FileExtensionRegistry::get('test1'));
    }

    /**
     * Получение
     *
     * @depends testAdd
     */
    public function testGetException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        FileExtensionRegistry::get('unknown');
    }

    /**
     * Удаление
     *
     * @depends testAdd
     */
    public function testDelete(): void
    {
        $this->assertTrue(FileExtensionRegistry::delete('test1'));
        $this->assertFalse(FileExtensionRegistry::delete('test1'));
    }
}
