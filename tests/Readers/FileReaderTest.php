<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Config\Readers\FileReader;
use PHPUnit\Framework\TestCase;

/**
 * Чтение конфига из файла
 */
class FileReaderTest extends TestCase
{
    /**
     * Осуществляет чтение
     */
    public function testRead(): void
    {
        $reader = new FileReader(__DIR__ . '/../Fixtures/test.config.php');
        $this->assertIsString($reader->read());
    }

    /**
     * Файл не найден
     */
    public function testReadFileNotFound(): void
    {
        $this->expectException(ReaderException::class);
        $reader = new FileReader(__DIR__ . '/../Fixtures/not-found.php');
        $reader->read();
    }

    /**
     * Файл не найден
     */
    public function testReadNotAccess(): void
    {
        $filePath = __DIR__ . '/../Fixtures/test.config.php';
        $this->expectException(ReaderException::class);
        chmod($filePath, 0000);
        $reader = new FileReader($filePath);
        try {
            $reader->read();
        } catch (ReaderException $exception) {
            chmod($filePath, 0775);

            throw $exception;
        }
    }
}
