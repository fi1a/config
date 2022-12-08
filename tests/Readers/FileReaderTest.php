<?php

declare(strict_types=1);

namespace Fi1a\Unit\Config\Readers;

use Fi1a\Config\Exceptions\ReaderException;
use Fi1a\Config\Readers\FileReader;
use Fi1a\Filesystem\Adapters\LocalAdapter;
use Fi1a\Filesystem\FileInterface;
use Fi1a\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

/**
 * Чтение конфига из файла
 */
class FileReaderTest extends TestCase
{
    /**
     * Возвращает файл
     */
    private function getFile(string $path): FileInterface
    {
        return (new Filesystem(new LocalAdapter(__DIR__ . '/../Resources')))
            ->factoryFile($path);
    }

    /**
     * Осуществляет чтение
     */
    public function testRead(): void
    {
        $reader = new FileReader($this->getFile('./test.config.php'));
        $this->assertIsString($reader->read());
    }

    /**
     * Файл не найден
     */
    public function testReadFileNotFound(): void
    {
        $this->expectException(ReaderException::class);
        $reader = new FileReader($this->getFile('./not-found.php'));
        $reader->read();
    }

    /**
     * Файл не найден
     */
    public function testReadNotAccess(): void
    {
        $filePath = __DIR__ . '/../Resources/test.config.php';
        $this->expectException(ReaderException::class);
        chmod($filePath, 0000);
        $reader = new FileReader($this->getFile('./test.config.php'));
        try {
            $reader->read();
        } catch (ReaderException $exception) {
            chmod($filePath, 0775);

            throw $exception;
        }
    }
}
