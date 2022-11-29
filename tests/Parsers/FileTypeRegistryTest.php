<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Parsers;

use Fi1a\Config\Exceptions\InvalidArgumentException;
use Fi1a\Config\Parsers\FileTypeRegistry;
use Fi1a\Config\Parsers\PHPParser;
use PHPUnit\Framework\TestCase;

/**
 * Реестр парсеров по расширениям файлов
 */
class FileTypeRegistryTest extends TestCase
{
    /**
     * Добавить
     */
    public function testAdd(): void
    {
        $this->assertTrue(FileTypeRegistry::add('test1', PHPParser::class));
        $this->assertFalse(FileTypeRegistry::add('test1', PHPParser::class));
    }

    /**
     * Получение
     *
     * @depends testAdd
     */
    public function testAddException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        FileTypeRegistry::add('test2', static::class);
    }

    /**
     * Наличие
     *
     * @depends testAdd
     */
    public function testHas(): void
    {
        $this->assertFalse(FileTypeRegistry::has('unknown'));
        $this->assertTrue(FileTypeRegistry::has('test1'));
    }

    /**
     * Получение
     *
     * @depends testAdd
     */
    public function testGet(): void
    {
        $this->assertEquals(PHPParser::class, FileTypeRegistry::get('test1'));
    }

    /**
     * Получение
     *
     * @depends testAdd
     */
    public function testGetException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        FileTypeRegistry::get('unknown');
    }

    /**
     * Удаление
     *
     * @depends testAdd
     */
    public function testDelete(): void
    {
        $this->assertTrue(FileTypeRegistry::delete('test1'));
        $this->assertFalse(FileTypeRegistry::delete('test1'));
    }
}
