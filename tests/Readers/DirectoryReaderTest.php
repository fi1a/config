<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Config\Readers\DirectoryReader;
use PHPUnit\Framework\TestCase;

/**
 * Чтение конфигов из директории
 */
class DirectoryReaderTest extends TestCase
{
    /**
     * Осуществляет чтение
     */
    public function testRead(): void
    {
        $reader = new DirectoryReader(__DIR__ . '/../Fixtures', '/(.)+\.php/');
        $result = $reader->read();
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
    }

    /**
     * Осуществляет чтение
     */
    public function testReadSkipByPattern(): void
    {
        $reader = new DirectoryReader(__DIR__ . '/../Fixtures', '/(.)+\.txt/');
        $result = $reader->read();
        $this->assertIsArray($result);
        $this->assertCount(0, $result);
    }

    /**
     * Осуществляет чтение (исключение при отсутствии директории)
     */
    public function testReadDirectoryNotFound(): void
    {
        $this->expectException(ReaderException::class);
        $reader = new DirectoryReader(__DIR__ . '/../not-found', '/(.)+\.php/');
        $reader->read();
    }

    /**
     * Осуществляет чтение (исключение при отсутствии прав на чтение директории)
     */
    public function testReadDirectoryNotAccess(): void
    {
        $this->expectException(ReaderException::class);
        $directory = __DIR__ . '/../Fixtures';
        chmod($directory, 0000);
        $reader = new DirectoryReader($directory, '/(.)+\.php/');
        try {
            $reader->read();
        } catch (ReaderException $exception) {
            chmod($directory, 0775);

            throw $exception;
        }
    }

    /**
     * Осуществляет чтение (исключение при отсутствии прав на чтение директории)
     */
    public function testReadFileNotAccess(): void
    {
        $this->expectException(ReaderException::class);
        $directory = __DIR__ . '/../Fixtures';
        chmod($directory . '/test.config.php', 0000);
        $reader = new DirectoryReader($directory, '/(.)+\.php/');
        try {
            $reader->read();
        } catch (ReaderException $exception) {
            chmod($directory . '/test.config.php', 0775);

            throw $exception;
        }
    }
}
